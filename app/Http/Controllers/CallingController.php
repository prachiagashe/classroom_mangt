<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallingData;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\CallingDataImport;

class CallingController extends Controller
{
    /**
     * Display a listing of the calling data.
     */
    public function index(Request $request)
    {
        $query = CallingData::query();

        // SEARCH (name + mobile + school)
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('student_name', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile_no', 'like', '%' . $request->search . '%')
                  ->orWhere('school_name', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER: RESPONSE
        if ($request->response && $request->response != 'all') {
            $query->where('response', $request->response);
        }

        // FILTER: CALL STATUS
        if ($request->status && $request->status != 'all') {
            $query->where('call_status', $request->status);
        }

        $callingData = $query->orderBy('id', 'desc')
                            ->paginate(10)
                            ->withQueryString();

        return view('calling.index', compact('callingData'));
    }

    /**
     * Show the form for creating a new calling entry.
     */
    public function create()
    {
        return view('calling.create');
    }

    /**
     * Store a newly created calling entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'mobile_no' => ['required','digits:10','regex:/^[6-9]\d{9}$/'],
            'response' => 'required|in:Positive,Negative,Pending',
            'call_status' => 'required|in:Done,Not Received,Pending',
        ], [
            'mobile_no.regex' => 'Mobile number must be a valid 10-digit number starting with 6-9',
        ]);

        // Auto-increment Sr No
        $lastSrNo = CallingData::max('sr_no') ?? 0;
        $validated['sr_no'] = $lastSrNo + 1;

        CallingData::create($validated);

        return redirect()->route('calling.index')
            ->with('success', 'Calling entry created successfully!');
    }

    /**
     * Handle Excel file upload.
     */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            \Log::info('Starting Excel import...');
            
            // Get all rows from Excel with header mapping
            $rows = Excel::toArray(new class implements WithHeadingRow {
                public function headingRow(): int
                {
                    return 1; // Headers are in row 1
                }
            }, $request->file('excel_file'))[0];
            
            \Log::info('Excel rows loaded', ['count' => count($rows)]);
            
            // Get last Sr No for auto-increment
            $lastSrNo = CallingData::max('sr_no') ?? 0;
            \Log::info('Starting Sr No from', ['sr_no' => $lastSrNo + 1]);
            
            $importedCount = 0;
            $skippedCount = 0;
            
            // Variable to track the last non-empty school name
            $lastSchoolName = null;
            
            // Process data rows (start from index 0 since headers are handled by WithHeadingRow)
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because headers are row 1, data starts from row 2
                \Log::info("Processing row " . $rowNumber, ['data' => $row]);
                
                // Header-based mapping (remove sr_no from Excel)
                $schoolName = $row['school_name'] ?? null;
                $studentName = $row['student_name'] ?? null;
                $mobile = $row['mobile'] ?? null;
                
                // Clean mobile number
                $mobile = preg_replace('/[^0-9]/', '', $mobile);
                
                // Clean other fields
                $schoolName = trim($schoolName);
                $studentName = trim($studentName);
                
                // Smart school name filling logic
                if (!empty($schoolName)) {
                    // If current row has school name, update lastSchoolName
                    $lastSchoolName = $schoolName;
                } else {
                    // If empty, use previous school name
                    $schoolName = $lastSchoolName ?? 'Unknown School';
                }
                
                \Log::info("Extracted data", [
                    'row' => $rowNumber,
                    'school_name' => $schoolName,
                    'student_name' => $studentName,
                    'mobile' => $mobile,
                    'last_school_name' => $lastSchoolName
                ]);
                
                // Skip invalid rows - if student_name OR mobile empty
                if (empty($studentName) && empty($mobile)) {
                    \Log::warning("Skipping row " . $rowNumber, ['reason' => 'no student name and no mobile']);
                    $skippedCount++;
                    continue;
                }
                
                // Insert data with auto-incremented Sr No
                CallingData::create([
                    'sr_no' => ++$lastSrNo,
                    'school_name' => $schoolName ?: 'Unknown School',
                    'student_name' => $studentName ?: '',
                    'mobile_no' => $mobile ?: '',
                    'response' => null,
                    'call_status' => null,
                    'visit_branch' => false,
                    'follow_up' => false,
                ]);
                
                $importedCount++;
                \Log::info("Imported row " . $rowNumber, ['sr_no' => $lastSrNo]);
            }
            
            \Log::info("Excel import completed", [
                'imported' => $importedCount,
                'skipped' => $skippedCount
            ]);
            
            return redirect()->route('calling.index')
                ->with('success', "Excel data uploaded successfully! Imported $importedCount records. Skipped $skippedCount empty rows.");
                
        } catch (\Exception $e) {
            \Log::error('Excel import error', ['message' => $e->getMessage()]);
            \Log::error('Exception trace', ['trace' => $e->getTraceAsString()]);
            
            // Provide helpful error message based on common issues
            $errorMessage = 'Error uploading Excel file: ' . $e->getMessage();
            
            if (strpos($e->getMessage(), 'Column') !== false && strpos($e->getMessage(), 'cannot be null') !== false) {
                $errorMessage = 'Excel format error: Required columns not found. Please ensure your Excel has columns: Sr No, School Name, Student Name, Mobile';
            } elseif (strpos($e->getMessage(), 'Invalid file') !== false) {
                $errorMessage = 'Invalid Excel file format. Please ensure the file is a valid Excel file (.xlsx, .xls, .csv).';
            } elseif (strpos($e->getMessage(), 'Undefined index') !== false) {
                $errorMessage = 'Excel format error: Missing required columns. Please ensure your Excel has columns: Sr No, School Name, Student Name, Mobile';
            }
            
            return redirect()->route('calling.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Show the form for editing the specified calling data.
     */
    public function edit($id)
    {
        $data = CallingData::findOrFail($id);
        return view('calling.edit', compact('data'));
    }

    /**
     * Update calling entry.
     */
    public function update(Request $request, $id)
    {
        $callingData = CallingData::findOrFail($id);
        
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'mobile_no' => ['required','digits:10','regex:/^[6-9]\d{9}$/'],
            'response' => 'required|in:Positive,Negative,Pending',
            'call_status' => 'required|in:Done,Not Received,Pending',
            'visit_branch' => 'nullable|boolean',
            'follow_up' => 'nullable|boolean',
            'follow_up_date' => 'nullable|date|required_if:follow_up,1',
        ], [
            'mobile_no.regex' => 'Mobile number must be a valid 10-digit number starting with 6-9',
            'follow_up_date.required_if' => 'Follow-up date is required when follow-up is checked',
        ]);

        // Handle checkbox values
        $validated['visit_branch'] = $request->has('visit_branch');
        $validated['follow_up'] = $request->has('follow_up');
        
        // Handle follow_up_date - only save if follow_up is checked
        if ($validated['follow_up'] && !empty($validated['follow_up_date'])) {
            $validated['follow_up_date'] = $validated['follow_up_date'];
        } else {
            $validated['follow_up_date'] = null;
        }

        $callingData->update($validated);

        // Check if request is AJAX (for inline updates) or form submission
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Calling entry updated successfully!'
            ]);
        }

        return redirect()->route('calling.index')
            ->with('success', 'Calling data updated successfully!');
    }

    /**
     * Update individual field via AJAX.
     */
    public function updateField(Request $request, $id)
    {
        try {
            // Debug logging
            \Log::info('UpdateField Request', [
                'id' => $id,
                'field' => $request->input('field'),
                'value' => $request->input('value'),
                'all_input' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method()
            ]);

            $call = CallingData::findOrFail($id);

            $allowedFields = ['response', 'call_status', 'visit_branch', 'follow_up', 'follow_up_date'];
            $field = $request->input('field');
            $value = $request->input('value');

            if (!in_array($field, $allowedFields)) {
                return response()->json(['success' => false, 'error' => 'Field not allowed: ' . $field]);
            }

            // Trim the value to remove any whitespace
            $value = trim($value);

            // Validation based on field type
            if ($field == 'call_status') {
                $validValues = ['Done', 'Not Received', 'Pending'];
                if (!in_array($value, $validValues)) {
                    return response()->json(['success' => false, 'error' => 'Invalid call status value: ' . $value]);
                }
            }

            if ($field == 'response') {
                $validValues = ['positive', 'negative', 'pending'];
                if (!in_array($value, $validValues)) {
                    return response()->json(['success' => false, 'error' => 'Invalid response value: ' . $value]);
                }
            }

            if ($field == 'follow_up_date') {
                // Handle empty date (should be null)
                if (empty($value)) {
                    $value = null;
                }
            }

            $call->$field = $value;
            $call->save();

            \Log::info('UpdateField Success', [
                'id' => $id,
                'field' => $field,
                'value' => $value,
                'saved_value' => $call->$field
            ]);

            return response()->json([
                'success' => true,
                'value' => $value,
                'message' => 'Updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('UpdateField Error', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete calling entry.
     */
    public function destroy($id)
    {
        try {
            $callingData = CallingData::findOrFail($id);
            $callingData->delete();
            
            return redirect()->route('calling.index')
                ->with('success', 'Student entry deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('calling.index')
                ->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }
}
