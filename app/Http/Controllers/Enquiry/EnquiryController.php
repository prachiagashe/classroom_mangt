<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Services\NotificationService;
use App\Traits\SendsAdmissionEmails;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EnquiryImport;

class EnquiryController extends Controller
{
    use SendsAdmissionEmails;
    public function index()
    {
        // Fetch enquiries with pagination (10 per page)
        $enquiries = Enquiry::latest()->paginate(10);

        return view('enquiry.enquiries.index', compact('enquiries'));
    }
    public function show($id)
{
    $enquiry = \App\Models\Enquiry::findOrFail($id);

    if (request()->ajax()) {
        return response()->json($enquiry);
    }

    return view('enquiry.enquiries.show', compact('enquiry'));
}

     // ✅ PRINT METHOD
    public function print($id)
    {
        $enquiry = Enquiry::findOrFail($id);
        
        // Parse foundation, course, source (handle both JSON and CSV)
        $foundation = $enquiry->foundation ?? '';
        $course = $enquiry->course ?? '';
        $source = $enquiry->source ?? '';
        
        // Try JSON decode first, fallback to CSV
        if (is_string($foundation)) {
            $decoded = json_decode($foundation, true);
            $foundation = $decoded !== null ? $decoded : array_map('trim', explode(',', $foundation));
        } else {
            $foundation = (array) $foundation;
        }
        if (is_string($course)) {
            $decoded = json_decode($course, true);
            $course = $decoded !== null ? $decoded : array_map('trim', explode(',', $course));
        } else {
            $course = (array) $course;
        }
        if (is_string($source)) {
            $decoded = json_decode($source, true);
            $source = $decoded !== null ? $decoded : array_map('trim', explode(',', $source));
        } else {
            $source = (array) $source;
        }
        
        // Remove empty values
        $foundation = array_filter($foundation);
        $course = array_filter($course);
        $source = array_filter($source);
        
        // Normalize medium for flexible matching
        $medium = strtolower(trim($enquiry->medium ?? ''));
        
        // Debug logging
        \Log::info('MEDIUM RAW:', [$enquiry->medium]);
        \Log::info('COURSE RAW:', [$enquiry->course]);
        \Log::info('COURSE ARRAY:', $course);
        \Log::info('PARENT FEEDBACK:', [$enquiry->parent_feedback]);
        
        // Parse address into lines
        $addressLines = explode("\n", $enquiry->address ?? '');
        $addressLine1 = $addressLines[0] ?? '';
        $addressLine2 = isset($addressLines[1]) ? implode("\n", array_slice($addressLines, 1)) : '';
        
        // Build checkbox mappings with exact DB values
        $checks = [];
        
        // Map medium checkboxes (flexible matching)
        $checks['semi_medium'] = str_contains($medium, 'semi');
        $checks['english_medium'] = str_contains($medium, 'english');
        $checks['cbse'] = str_contains($medium, 'cbse');
        $checks['icse'] = str_contains($medium, 'icse');
        
        // Map foundation checkboxes (exact match)
        $checks['scholarship'] = in_array('Scholarship', $foundation);
        $checks['dr_homibhabha'] = in_array('Dr. Homibhabha', $foundation);
        $checks['olympaid'] = in_array('Olympaid', $foundation);
        $checks['mtse'] = in_array('MTSE', $foundation);
        
        // Map course checkboxes (exact match)
        $checks['neet'] = in_array('NEET', $course);
        $checks['jee'] = in_array('JEE', $course);
        $checks['mht_cet'] = in_array('MHT-CET', $course);
        $checks['rept'] = in_array('REPT', $course);
        $checks['test_series'] = in_array('TEST SERIES', $course);
        $checks['crash_course'] = in_array('CRASH C.', $course);
        
        // Map source checkboxes (exact match)
        $checks['boost_tse_exam'] = in_array('Boost/TSE Exam', $source);
        $checks['paper_advt'] = in_array('Paper Advertisement', $source);
        $checks['tv_advt'] = in_array('TV Advt', $source);
        $checks['students_ref'] = in_array('Student\'s Ref', $source);
        $checks['employee_ref'] = in_array('Employee Ref', $source);
        $checks['other_ref'] = in_array('Other Ref', $source);
        
        // Map remarks checkboxes (only ONE can be checked)
        $checks['hot'] = ($enquiry->remarks === 'Hot');
        $checks['warm'] = ($enquiry->remarks === 'Warm');
        $checks['cold'] = ($enquiry->remarks === 'Cold');
        
        // Build DOB digits array
        $dobDigits = [];
        $dobFormatted = $enquiry->dob ? $enquiry->dob->format('dmY') : '';
        if (strlen($dobFormatted) >= 8) {
            $dobDigits = str_split($dobFormatted);
        }
        
        // Build siblings array
        $siblings = [
            ['name' => $enquiry->sibling1 ?? '', 'class' => '', 'medium' => ''],
            ['name' => $enquiry->sibling2 ?? '', 'class' => '', 'medium' => ''],
        ];
        
        // Build references array
        $references = [
            ['name' => $enquiry->reference1 ?? '', 'class' => '', 'medium' => ''],
            ['name' => $enquiry->reference2 ?? '', 'class' => '', 'medium' => ''],
        ];
        
        // Build counselling checks (all unchecked by default - can be populated from saved data)
        $counsellingChecks = [];
        
        // Build notice checks (all unchecked by default - can be populated from saved data)
        $noticeChecks = [];
        
        // Form array structure for print blade
        $form = [
            'enquiry_no' => $enquiry->enquiry_no ?? '',
            'date' => $enquiry->date ? $enquiry->date->format('dmY') : '',
            'branch_code' => $enquiry->branch_code ?? 'BCPL-ND-CY',
            
            // Name fields
            'first_name' => $enquiry->first_name ?? '',
            'middle_name' => $enquiry->middle_name ?? '',
            'surname' => $enquiry->surname ?? '',
            
            // Academic details
            'class' => $enquiry->class ?? '',
            'college_time' => $enquiry->school_time ?? '',
            'last_year_percentage' => $enquiry->last_year_percentage ?? '',
            'college_name' => $enquiry->school_name ?? '',
            
            // Medium/Board
            'medium' => $enquiry->medium ?? '',
            'board' => $enquiry->medium ?? '', // Using medium field for board
            
            // Personal details
            'dob' => $enquiry->dob ? $enquiry->dob->format('dmY') : '',
            'father_occupation' => $enquiry->father_occupation ?? '',
            
            // Contact details
            'parent_mobile' => $enquiry->parent_mobile ?? '',
            'student_mobile' => $enquiry->student_mobile ?? '',
            'whatsapp_mobile' => $enquiry->whatsapp ?? '',
            
            // Address
            'address_line_1' => $addressLine1,
            'address_line_2' => $addressLine2,
            
            // Foundation and courses
            'foundation' => implode(',', $foundation),
            'course' => implode(',', $course),
            
            // Siblings and references
            'siblings' => $siblings,
            'references' => $references,
            
            // Source and remarks
            'source' => implode(',', $source),
            'remarks' => $enquiry->remarks ?? '',
            
            // Feedback
            'parent_feedback' => $enquiry->parent_feedback ?? '',
            
            // Checkbox mappings
            'checks' => $checks,
            'dob_digits' => $dobDigits,
            'enquiry_no' => $enquiry->enquiry_no,
            'enquiry_date' => $enquiry->date ? $enquiry->date->format('dmY') : '',
            'counselling_checks' => $counsellingChecks,
            'notice_checks' => $noticeChecks,
            
            // Logo source
            'logo_src' => asset('images/bansal-logo.png'),
        ];
        
        return view('enquiry.enquiries.print', [
            'enquiry' => $enquiry,   // existing usage
            'form' => $form,         // properly structured form data
            'logoSrc' => asset('images/bansal-logo.png'),
        ]);
    }

     public function create()
    {
        return view('enquiry.form');
    }

    public function followup($id)
{
    $enquiry = Enquiry::findOrFail($id);

    $enquiry->update([
        'status' => 'pending'
    ]);

    return back()->with('success', 'Moved to Follow-up!');
}

public function confirm($id)
{
    $enquiry = Enquiry::findOrFail($id);

    \DB::beginTransaction();
    
    try {
        // Log the confirmation process
        \Log::info('Starting admission confirmation for enquiry ID: ' . $enquiry->id);
        
        // Update enquiry status to confirmed
        $enquiry->update([
            'status' => 'confirmed'
        ]);

        // WhatsApp Confirmation Message (Deferred to speed up response)
        defer(function () use ($enquiry) {
            try {
                app(\App\Services\WhatsAppService::class)->sendMessage('confirmation', $enquiry);
            } catch (\Exception $e) {
                \Log::error('Deferred WhatsApp failed: ' . $e->getMessage());
            }
        });

        $contact = $enquiry->parent_mobile ?? $enquiry->student_mobile ?? '';
        $className = $enquiry->class ?? '';
        $studentName = trim(($enquiry->first_name ?? '') . ' ' . ($enquiry->middle_name ?? '') . ' ' . ($enquiry->surname ?? ''));

        // Prevent Duplicate Student Entry
        $duplicate = \App\Models\Admission::where('student_name', $studentName)
                                          ->where('contact', $contact)
                                          ->where('class', $className)
                                          ->first();
        if ($duplicate) {
             return redirect()->back()->with('error', 'Student already exists in this class!');
        }

        // Generate unique class-based roll number
        $classPrefix = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $className));
        if (empty($classPrefix)) $classPrefix = 'GEN';
        $baseRoll = sprintf("%s-%s", $classPrefix, date('y'));
        
        $lastRecord = \App\Models\Admission::where('class', $className)
                            ->where('roll_number', 'like', "{$baseRoll}-%")
                            ->orderByRaw("CAST(SUBSTRING_INDEX(roll_number, '-', -1) AS UNSIGNED) DESC")
                            ->first();

        $nextSequence = 1;
        if ($lastRecord) {
            $parts = explode('-', $lastRecord->roll_number);
            $nextSeq = intval(end($parts));
            if ($nextSeq > 0) $nextSequence = $nextSeq + 1;
        }

        while (\App\Models\Admission::where('roll_number', sprintf("%s-%04d", $baseRoll, $nextSequence))->exists()) {
            $nextSequence++;
        }
        $rollNumber = sprintf("%s-%04d", $baseRoll, $nextSequence);

        // Create admission record from enquiry data
        $admissionData = [
            'enquiry_id' => $enquiry->id,
            'student_name' => $studentName,
            'parent_name' => '', // Will be populated from admission form data if available
            'contact' => $contact,
            'email' => $enquiry->email ?? '',
            'class' => $className,
            'roll_number' => $rollNumber,
            'admission_date' => now()->toDateString(),
            'fee_status' => 'pending',
            'payment_mode' => null,
            'installment_type' => null,
            'installment_count' => null,
            'installment_amount' => null,
            'installment_start_date' => null,
            'remarks' => json_encode([
                'source_enquiry_id' => $enquiry->id,
                'enquiry_no' => $enquiry->enquiry_no,
                'created_from' => 'enquiry_confirmation',
                'original_data' => [
                    'school_name' => $enquiry->school_name,
                    'medium' => $enquiry->medium,
                    'foundation' => $enquiry->foundation ?? [],
                    'course' => $enquiry->course ?? [],
                    'source' => $enquiry->source ?? [],
                ]
            ]),
            'total_fee' => $enquiry->total_fees ?? 0,
            'paid_amount' => 0,
            'address' => $enquiry->address ?? '',
            'date_of_birth' => $enquiry->dob ?? null,
            'blood_group' => null,
            'previous_school' => $enquiry->school_name ?? '',
        ];

        // Check if this enquiry came from admission form and extract additional data
        if ($enquiry->source === 'Admission Form' && !empty($enquiry->parent_feedback)) {
            $admissionFormData = json_decode($enquiry->parent_feedback, true);
            if ($admissionFormData && isset($admissionFormData['form_type']) && $admissionFormData['form_type'] === 'admission_form') {
                // Extract parent name from admission form data
                $fatherName = $admissionFormData['father_name'] ?? '';
                $motherName = $admissionFormData['mother_name'] ?? '';
                if (!empty($fatherName) && !empty($motherName)) {
                    $admissionData['parent_name'] = trim($fatherName . ' & ' . $motherName);
                } elseif (!empty($fatherName)) {
                    $admissionData['parent_name'] = $fatherName;
                } elseif (!empty($motherName)) {
                    $admissionData['parent_name'] = $motherName;
                }

                // Update remarks with admission form data
                $existingRemarks = json_decode($admissionData['remarks'], true);
                $existingRemarks['admission_form_data'] = $admissionFormData;
                $admissionData['remarks'] = json_encode($existingRemarks);

                // Use final fees from admission form if available
                if (isset($admissionFormData['fees_breakdown']['final_fees'])) {
                    $admissionData['total_fee'] = $admissionFormData['fees_breakdown']['final_fees'];
                }
            }
        }

        // Check if admission already exists to avoid duplicates during status switching
        $admission = \App\Models\Admission::where('enquiry_id', $enquiry->id)->first();
        
        if (!$admission) {
            $admission = \App\Models\Admission::create($admissionData);
            \Log::info('Created admission record with ID: ' . $admission->id);

            // Send confirmation email (Deferred)
            defer(function () use ($admission) {
                try {
                    $this->sendAdmissionConfirmationEmail($admission->student_name, $admission->email, $admission->class);
                } catch (\Exception $e) {
                    \Log::error('Deferred confirmation email failed: ' . $e->getMessage());
                }
            });

            // Create corresponding fee_payment record
            $feeAmount = $admissionData['total_fee'];
            \App\Models\FeePayment::create([
                'admission_id' => $admission->id,
                'amount' => $feeAmount,
                'payment_mode' => 'pending',
                'payment_date' => now()->toDateString(),
                'transaction_id' => null,
                'remarks' => 'Initial payment record created from enquiry confirmation - Enquiry ID: ' . $enquiry->id . ' | Final Fees: ' . $feeAmount,
            ]);
            
            \Log::info('Created fee_payment record for admission ID: ' . $admission->id . ' with amount: ' . $feeAmount);

            // Handle Student Account Creation
            if (!empty($enquiry->email)) {
                $userExists = \App\Models\User::where('email', $enquiry->email)->exists();
                if (!$userExists) {
                    $tempPassword = \Illuminate\Support\Str::random(10);
                    
                    \App\Models\User::create([
                        'name' => $admission->student_name,
                        'email' => $enquiry->email,
                        'password' => bcrypt($tempPassword),
                        'role' => 'student',
                        'phone' => $admission->contact,
                        'force_password_change' => true,
                    ]);

                    \Log::info('Student account created for ' . $enquiry->email);
                    
                    // Deferred Emails & Notifications
                    defer(function () use ($admission, $enquiry, $tempPassword, $className, $rollNumber) {
                        try {
                            $this->sendStudentLoginCredentialsEmail(
                                $admission->student_name, 
                                $enquiry->email, 
                                $tempPassword, 
                                route('login')
                            );

                            // Send welcome notification to the new student
                            $newUser = \App\Models\User::where('email', $enquiry->email)->first();
                            if ($newUser) {
                                \App\Services\NotificationService::notifyUser(
                                    $newUser->id,
                                    'Admission Confirmed',
                                    "Welcome to StudyFlow Classes! Your admission for Class {$className} has been confirmed. Roll No: {$rollNumber}.",
                                    'admission',
                                    ['admission_id' => $admission->id, 'roll_number' => $rollNumber]
                                );
                            }
                        } catch (\Exception $e) {
                            \Log::error('Deferred account emails/notifications failed: ' . $e->getMessage());
                        }
                    });
                } else {
                    \Log::info('Student account for ' . $enquiry->email . ' already exists, skipping creation.');
                    
                    // Notify existing student about admission (Deferred)
                    defer(function () use ($enquiry, $className, $rollNumber, $admission) {
                        try {
                            \App\Services\NotificationService::notifyStudentByEmail(
                                $enquiry->email,
                                'Admission Confirmed',
                                "Welcome to StudyFlow Classes! Your admission for Class {$className} has been confirmed. Roll No: {$rollNumber}.",
                                'admission',
                                ['admission_id' => $admission->id, 'roll_number' => $rollNumber]
                            );
                        } catch (\Exception $e) {
                            \Log::error('Deferred existing student email notification failed: ' . $e->getMessage());
                        }
                    });
                }
            }
        }

        \DB::commit();

        return redirect()
            ->route('enquiry.admissions.index')
            ->with('success', 'Enquiry status updated to Confirmed!');

    } catch (\Exception $e) {
        \DB::rollback();
        \Log::error('Failed to confirm admission: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()
            ->with('error', 'Failed to confirm admission: ' . $e->getMessage());
    }
}


public function reject($id)
{
    $enquiry = Enquiry::findOrFail($id);

    \DB::beginTransaction();
    try {
        // 1. Find and delete associated admissions and their fee payments
        $admission = \App\Models\Admission::where('enquiry_id', $id)->first();
        if ($admission) {
            // Delete associated fee payments first
            \App\Models\FeePayment::where('admission_id', $admission->id)->delete();
            // Delete the admission record
            $admission->delete();
            \Log::info("Deleted admission and fees for rejected enquiry ID: {$id}");
        }

        // 2. Update enquiry status to rejected
        $enquiry->update([
            'status' => 'rejected'
        ]);

        \DB::commit();

        $this->sendAdmissionRejectionEmail(
            trim(($enquiry->first_name ?? '') . ' ' . ($enquiry->middle_name ?? '') . ' ' . ($enquiry->surname ?? '')),
            $enquiry->email,
            $enquiry->class ?? 'Unknown'
        );

        return back()->with('success', 'Enquiry Rejected and associated Admission record removed!');
    } catch (\Exception $e) {
        \DB::rollback();
        \Log::error("Failed to reject enquiry {$id}: " . $e->getMessage());
        return back()->with('error', 'Failed to reject enquiry: ' . $e->getMessage());
    }
}

public function history($id)
{
    $enquiry = Enquiry::findOrFail($id);
    
    // Get all follow-ups for this enquiry ordered by creation date
    $followUps = \App\Models\FollowUp::where('enquiry_id', $id)
        ->orderBy('created_at', 'asc')
        ->get();

    return view('enquiry.enquiries.history', compact('enquiry', 'followUps'));
}

public function update(Request $request, $id)
{
    $enquiry = Enquiry::findOrFail($id);
    
    // Comprehensive validation for update
    $validated = $request->validate([
        // Name fields
        'first_name' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:2|max:50',
        'middle_name' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:2|max:50',
        'surname' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:2|max:50',
               // Academic fields
        'class' => 'required|string|max:50',
        'school_time' => 'nullable|string|max:255',
        'last_year_percentage' => 'required|numeric|min:0|max:100',
        'school_name' => 'nullable|string|max:150',
        'medium' => 'nullable|string|max:50',
        
        // Contact fields
        'parent_mobile' => 'required|regex:/^[6-9][0-9]{9}$/',
        'student_mobile' => 'required|regex:/^[6-9][0-9]{9}$/',
        'whatsapp' => 'required|regex:/^[6-9][0-9]{9}$/',
        'email' => 'required|string|email:rfc,dns|max:255',
        'address' => 'required|string|min:10|max:1000',
        
        // Parent Information
        'father_occupation' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
        
        // Sibling & Reference fields
        'sibling1' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/|min:2',
        'sibling2' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/|min:2',
        'reference1' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/|min:2',
        'reference2' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/|min:2',
        
        // Foundation & Courses
        'foundation' => 'nullable|string',
        'course' => 'nullable|string',
        'source' => 'nullable|string',
        
        // Fees
        'total_fees' => 'required|numeric|gt:0',
        'discount_fees' => 'nullable|numeric|min:0|lte:total_fees',
        'final_fees' => 'nullable|numeric|min:0',
        
        // Other
        'remarks' => 'nullable|string|max:1000',
        'parent_feedback' => 'nullable|string|max:1000',
        'status' => 'required|string|in:new,follow-up,confirmed,rejected',
        'dob' => 'nullable|date',
        'gender' => 'nullable|string|in:male,female,other',
    ], [
        // Custom error messages
        'first_name.regex' => 'First Name must contain only alphabets (no spaces or numbers)',
        'middle_name.regex' => 'Middle Name must contain only alphabets (no spaces or numbers)',
        'surname.regex' => 'Surname must contain only alphabets (no spaces or numbers)',
        'first_name.min' => 'First Name must be at least 2 characters',
        'middle_name.min' => 'Middle Name must be at least 2 characters',
        'surname.min' => 'Surname must be at least 2 characters',
        'parent_mobile.required' => 'Parent mobile is required',
        'parent_mobile.regex' => 'Parent mobile must be exactly 10 digits and start with 6, 7, 8, or 9',
        'student_mobile.required' => 'Student mobile is required',
        'student_mobile.regex' => 'Student mobile must be exactly 10 digits and start with 6, 7, 8, or 9',
        'whatsapp.required' => 'WhatsApp number is required',
        'whatsapp.regex' => 'WhatsApp number must be exactly 10 digits and start with 6, 7, 8, or 9',
        'email.required' => 'Email address is required',
        'email.email' => 'Enter valid email address (e.g., user@example.com)',
        'address.required' => 'Address is required',
        'address.min' => 'Address must be at least 10 characters',
        'father_occupation.required' => 'Father occupation is required',
        'father_occupation.regex' => 'Father occupation may only contain alphabets and spaces',
        'last_year_percentage.required' => 'Percentage is required',
        'last_year_percentage.numeric' => 'Percentage must be a number',
        'last_year_percentage.min' => 'Percentage cannot be negative',
        'last_year_percentage.max' => 'Percentage cannot exceed 100',
    ]);
    
    // Convert CSV strings to arrays for casted fields
    foreach (['foundation', 'course', 'source'] as $field) {
        if ($request->has($field) && is_string($request->input($field))) {
            $validated[$field] = array_map('trim', explode(',', $request->input($field)));
        }
    }
    
    // Handle fee calculation
    if (isset($validated['total_fees']) || isset($validated['discount_fees'])) {
        $total = floatval($validated['total_fees'] ?? $enquiry->total_fees);
        $discount = floatval($validated['discount_fees'] ?? $enquiry->discount_fees);
        $validated['final_fees'] = $total - $discount;
    }
    
    $enquiry->update($validated);
    
    // Sync with Admission model if it exists
    $admission = \App\Models\Admission::where('enquiry_id', $enquiry->id)->first();
    if ($admission) {
        $studentName = trim(($validated['first_name'] ?? $enquiry->first_name) . ' ' . ($validated['middle_name'] ?? $enquiry->middle_name) . ' ' . ($validated['surname'] ?? $enquiry->surname));
        
        $admission->update([
            'student_name' => $studentName,
            'email' => $validated['email'] ?? $enquiry->email,
            'contact' => $validated['parent_mobile'] ?? $enquiry->parent_mobile,
            'date_of_birth' => $validated['dob'] ?? $enquiry->dob,
            'class' => $validated['class'] ?? $enquiry->class,
            'total_fee' => $validated['final_fees'] ?? $enquiry->final_fees,
            'total_fees' => $validated['total_fees'] ?? $enquiry->total_fees,
            'discount_fees' => $validated['discount_fees'] ?? $enquiry->discount_fees,
            'final_fees' => $validated['final_fees'] ?? $enquiry->final_fees,
        ]);
        
        // Sync with User model if it exists
        $user = \App\Models\User::where('email', $enquiry->getOriginal('email'))
            ->orWhere('email', $enquiry->email)
            ->first();
            
        if ($user) {
            $user->update([
                'name' => $studentName,
                'email' => $validated['email'] ?? $enquiry->email,
                'phone' => $validated['parent_mobile'] ?? $enquiry->parent_mobile
            ]);
        }
    }

    return redirect()->route('enquiry.enquiries.show', $id)->with('success', 'Enquiry updated successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new EnquiryImport, $request->file('file'));
            return redirect()->route('enquiry.enquiries.index')->with('success', 'Enquiries imported successfully!');
        } catch (\Exception $e) {
            \Log::error('Enquiry Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $enquiry = Enquiry::findOrFail($id);

        if (strtolower($enquiry->status) === 'confirmed') {
            return back()->with('error', 'Confirmed admission enquiries cannot be deleted.');
        }

        \DB::beginTransaction();
        try {
            // Delete related follow-ups
            \App\Models\FollowUp::where('enquiry_id', $id)->delete();
            
            // Delete enquiry
            $enquiry->delete();

            \DB::commit();
            return back()->with('success', 'Enquiry deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("Failed to delete enquiry {$id}: " . $e->getMessage());
            return back()->with('error', 'Failed to delete enquiry: ' . $e->getMessage());
        }
    }
}