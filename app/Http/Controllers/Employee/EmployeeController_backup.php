<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        $employees = Employee::orderBy('created_at', 'desc')->get();
        return view('employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('employee.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        Log::info('Employee Store Request Data:', $request->all());
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/|max:10',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'employment_type' => 'required|string|max:255',
            'status' => 'nullable|in:Active,Inactive,On Leave',
            
            // Salary validation
            'basic_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:monthly,hourly,yearly',
            'payment_method' => 'required|in:bank_transfer,cash,upi',
            
            // Conditional bank details validation
            'bank_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'account_number' => 'required_if:payment_method,bank_transfer|string|regex:/^[A-Za-z0-9]{12}$/|max:12',
            'IFSC_code' => 'required_if:payment_method,bank_transfer|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/|max:11',
            
            // Education validation
            'education_degree.*' => 'nullable|string|max:255',
            'education_institution.*' => 'nullable|string|max:255',
            'education_year.*' => 'nullable|integer|min:1900|max:' . date('Y'),
            'education_grade.*' => 'nullable|string|max:50',
            
            // Experience validation
            'experience_organization.*' => 'nullable|string|max:255',
            'experience_role.*' => 'nullable|string|max:255',
            'experience_start_date.*' => 'nullable|date',
            'experience_end_date.*' => 'nullable|date',
            'experience_total.*' => 'nullable|string|max:100',
        ], [
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
            'account_number.regex' => 'Account number must be exactly 12 characters (letters and numbers only).',
            'account_number.max' => 'Account number must be exactly 12 characters.',
            'IFSC_code.regex' => 'IFSC code must be in format: 4 letters + 0 + 6 alphanumeric characters (e.g., SBIN0001234).',
            'IFSC_code.max' => 'IFSC code must be exactly 11 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Generate employee code
            $employeeCode = Employee::generateEmployeeCode();

            // Process education entries
            $educationEntries = [];
            if ($request->has('education_degree')) {
                foreach ($request->education_degree as $key => $degree) {
                    if (!empty($degree) && !empty($request->education_institution[$key])) {
                        $educationEntries[] = [
                            'degree' => $degree,
                            'institution' => $request->education_institution[$key] ?? '',
                            'year_of_passing' => $request->education_year[$key] ?? '',
                            'grade' => $request->education_grade[$key] ?? '',
                        ];
                    }
                }
            }

            // Process experience entries
            $experienceEntries = [];
            if ($request->has('experience_organization')) {
                foreach ($request->experience_organization as $key => $organization) {
                    if (!empty($organization) && !empty($request->experience_role[$key])) {
                        $experienceEntries[] = [
                            'organization' => $organization,
                            'role' => $request->experience_role[$key] ?? '',
                            'start_date' => $request->experience_start_date[$key] ?? '',
                            'end_date' => $request->experience_end_date[$key] ?? '',
                            'total_experience' => $request->experience_total[$key] ?? '',
                        ];
                    }
                }
            }

            // Create employee
            $employee = Employee::create([
                'employee_code' => $employeeCode,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'designation' => $request->designation,
                'department' => $request->department,
                'joining_date' => $request->joining_date,
                'employment_type' => $request->employment_type,
                'status' => $request->status ?? 'Active',
                'basic_salary' => $request->basic_salary,
                'salary_type' => $request->salary_type,
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'IFSC_code' => $request->IFSC_code,
                'education' => !empty($educationEntries) ? Employee::formatEducation($educationEntries) : null,
                'experience' => !empty($experienceEntries) ? Employee::formatExperience($experienceEntries) : null,
            ]);

            return redirect()
                ->route('employee.index')
                ->with('success', 'Employee added successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error creating employee: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id . '|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/|max:10',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'employment_type' => 'required|string|max:255',
            'status' => 'nullable|in:Active,Inactive,On Leave',
            
            // Salary validation
            'basic_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:monthly,hourly,yearly',
            'payment_method' => 'required|in:bank_transfer,cash,upi',
            
            // Conditional bank details validation
            'bank_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'account_number' => 'required_if:payment_method,bank_transfer|string|regex:/^[A-Za-z0-9]{12}$/|max:12',
            'IFSC_code' => 'required_if:payment_method,bank_transfer|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/|max:11',
            
            // Education validation
            'education_degree.*' => 'nullable|string|max:255',
            'education_institution.*' => 'nullable|string|max:255',
            'education_year.*' => 'nullable|integer|min:1900|max:' . date('Y'),
            'education_grade.*' => 'nullable|string|max:50',
            
            // Experience validation
            'experience_organization.*' => 'nullable|string|max:255',
            'experience_role.*' => 'nullable|string|max:255',
            'experience_start_date.*' => 'nullable|date',
            'experience_end_date.*' => 'nullable|date',
            'experience_total.*' => 'nullable|string|max:100',
        ], [
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
            'account_number.regex' => 'Account number must be exactly 12 characters (letters and numbers only).',
            'account_number.max' => 'Account number must be exactly 12 characters.',
            'IFSC_code.regex' => 'IFSC code must be in format: 4 letters + 0 + 6 alphanumeric characters (e.g., SBIN0001234).',
            'IFSC_code.max' => 'IFSC code must be exactly 11 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Process education entries
            $educationEntries = [];
            if ($request->has('education_degree')) {
                foreach ($request->education_degree as $key => $degree) {
                    if (!empty($degree) && !empty($request->education_institution[$key])) {
                        $educationEntries[] = [
                            'degree' => $degree,
                            'institution' => $request->education_institution[$key] ?? '',
                            'year_of_passing' => $request->education_year[$key] ?? '',
                            'grade' => $request->education_grade[$key] ?? '',
                        ];
                    }
                }
            }

            // Process experience entries
            $experienceEntries = [];
            if ($request->has('experience_organization')) {
                foreach ($request->experience_organization as $key => $organization) {
                    if (!empty($organization) && !empty($request->experience_role[$key])) {
                        $experienceEntries[] = [
                            'organization' => $organization,
                            'role' => $request->experience_role[$key] ?? '',
                            'start_date' => $request->experience_start_date[$key] ?? '',
                            'end_date' => $request->experience_end_date[$key] ?? '',
                            'total_experience' => $request->experience_total[$key] ?? '',
                        ];
                    }
                }
            }

            // Update employee
            $employee->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'designation' => $request->designation,
                'department' => $request->department,
                'joining_date' => $request->joining_date,
                'employment_type' => $request->employment_type,
                'status' => $request->status ?? 'Active',
                'basic_salary' => $request->basic_salary,
                'salary_type' => $request->salary_type,
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'IFSC_code' => $request->IFSC_code,
                'education' => !empty($educationEntries) ? Employee::formatEducation($educationEntries) : null,
                'experience' => !empty($experienceEntries) ? Employee::formatExperience($experienceEntries) : null,
            ]);

            return redirect()
                ->route('employee.index')
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error updating employee: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            return redirect()
                ->route('employee.index')
                ->with('success', 'Employee deleted successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->route('employee.index')
                ->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}