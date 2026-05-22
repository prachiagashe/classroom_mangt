<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\User;
use App\Traits\SendsEmployeeEmails;
use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class EmployeeController extends Controller
{
    use SendsEmployeeEmails;
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request)
    {
        $query = Employee::orderBy('created_at', 'desc');
        
        // Filter by search term
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('employee_code', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $statusMap = [
                'active' => 'Active',
                'on_leave' => 'On Leave',
                'inactive' => 'Inactive'
            ];
            $status = $statusMap[$request->get('status')] ?? $request->get('status');
            $query->where('status', $status);
        }
        
        $employees = $query->get();
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
            // Personal Information Validation
            'first_name' => 'required|string|max:50|regex:/^[A-Za-z\s]+$/',
            'middle_name' => 'nullable|string|max:50|regex:/^[A-Za-z\s]+$/',
            'last_name' => 'required|string|max:50|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|email:rfc,dns|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z.-]+\.[A-Za-z]{2,}$/|unique:employees,email|unique:users,email|max:255',
            'phone' => 'required|regex:/^[6-9][0-9]{9}$/|not_in:0000000000',
            'date_of_birth' => 'required|date|after:1950-01-01|before:-18 years',
            'gender' => 'required|in:male,female,other',
            
            // Professional Information Validation
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'employment_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            
            // Salary Details Validation
            'basic_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:monthly,hourly,yearly',
            'payment_method' => 'required|in:bank_transfer,cash,upi',
            
            // Bank Details Validation
            'bank_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:255|regex:/^[A-Za-z\s]+$/',
            'account_number' => 'required_if:payment_method,bank_transfer|nullable|digits_between:9,18',
            'IFSC_code' => 'required_if:payment_method,bank_transfer|nullable|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/|max:11',
            
            // Education Details Validation
            'education_degree.*' => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'education_institution.*' => 'required|string|max:150|regex:/^[A-Za-z\s]+$/',
            'education_year.*' => 'required|digits:4|integer|max:' . date('Y'),
            'education_grade.*' => 'nullable|numeric|min:0|max:100',
            
            // Experience Type Validation
            'experience_type' => 'required|in:fresher,experienced',
            
            // Experience Details Validation
            'experience_organization.*' => 'required_if:experience_type,experienced|nullable|string|max:255|regex:/^[A-Za-z\s]+$/',
            'experience_role.*' => 'required_if:experience_type,experienced|nullable|string|max:255|regex:/^[A-Za-z\s]+$/',
            'experience_start_date.*' => 'required_if:experience_type,experienced|nullable|date|before_or_equal:today',
            'experience_end_date.*' => 'required_if:experience_type,experienced|nullable|date|after:experience_start_date.*',
            'experience_total.*' => 'nullable|string|max:100',
            
            // Academic Assignment Validation
            'assigned_classes' => 'required_if:designation,teacher,Teacher|nullable|array|min:1',
            'assigned_classes.*' => 'nullable|string|max:50',
            'assigned_subjects' => 'required_if:designation,teacher,Teacher|nullable|array|min:1',
            'assigned_subjects.*' => 'nullable|string|max:50',
        ], [
            // Personal Information Error Messages
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name can only contain alphabets and spaces.',
            'middle_name.regex' => 'Middle name can only contain alphabets and spaces.',
            'last_name.required' => 'Last name is required.',
            'last_name.regex' => 'Last name can only contain alphabets and spaces.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address with proper domain format.',
            'email.regex' => 'Please enter a valid email address with proper domain structure and valid top-level domain.',
            'email.unique' => 'Email already exists.',
            'phone.required' => 'Mobile number is required.',
            'phone.regex' => 'Mobile number must be exactly 10 digits starting with 6, 7, 8, or 9.',
            'phone.not_in' => 'Mobile number cannot be all zeros.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must not be today.',
            'date_of_birth.before_or_equal' => 'Employee must be at least 18 years old.',
            'gender.required' => 'Gender is required.',
            
            // Professional Information Error Messages
            'designation.required' => 'Designation is required.',
            'department.required' => 'Department is required.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.date' => 'Please enter a valid joining date.',
            'employment_type.required' => 'Employment type is required.',
            'status.required' => 'Status is required.',
            
            // Salary Details Error Messages
            'basic_salary.required' => 'Basic salary is required.',
            'basic_salary.numeric' => 'Basic salary must be a number.',
            'basic_salary.min' => 'Basic salary cannot be negative.',
            'salary_type.required' => 'Salary type is required.',
            'payment_method.required' => 'Payment method is required.',
            
            // Bank Details Error Messages
            'bank_name.required_if' => 'Bank name is required for bank transfer.',
            'bank_name.regex' => 'Bank name can only contain alphabets and spaces.',
            'account_number.required_if' => 'Account number is required for bank transfer.',
            'account_number.digits_between' => 'Account number must be between 9 and 18 digits.',
            'IFSC_code.required_if' => 'IFSC code is required for bank transfer.',
            'IFSC_code.regex' => 'Please enter a valid IFSC code (e.g. SBIN0001234).',
            'IFSC_code.max' => 'IFSC code must be exactly 11 characters.',
            
            // Education Details Error Messages
            'education_degree.*.required' => 'Degree is required.',
            'education_degree.*.regex' => 'Degree can only contain alphabets and spaces.',
            'education_institution.*.required' => 'Institution name is required.',
            'education_institution.*.regex' => 'Institution name can only contain alphabets and spaces.',
            'education_year.*.required' => 'Year of passing is required.',
            'education_year.*.digits' => 'Year of passing must be exactly 4 digits.',
            'education_year.*.max' => 'Year of passing cannot be in the future.',
            'education_grade.*.numeric' => 'Grade/Percentage must be a number.',
            'education_grade.*.min' => 'Grade/Percentage cannot be negative.',
            'education_grade.*.max' => 'Grade/Percentage cannot exceed 100.',
            
            // Experience Details Error Messages
            'experience_organization.*.required' => 'Organization name is required.',
            'experience_organization.*.regex' => 'Organization name can only contain alphabets and spaces.',
            'experience_role.*.required' => 'Role is required.',
            'experience_role.*.regex' => 'Role can only contain alphabets and spaces.',
            'experience_start_date.*.required' => 'Start date is required.',
            'experience_start_date.*.date' => 'Please enter a valid start date.',
            'experience_end_date.*.required' => 'End date is required.',
            'experience_end_date.*.date' => 'Please enter a valid end date.',
            'experience_end_date.*.after' => 'End date must be after start date.',
            
            // Academic Assignment Error Messages
            'assigned_classes.required_if' => 'Classes must be assigned to teachers.',
            'assigned_subjects.required_if' => 'Subjects must be assigned to teachers.',
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

            // Process experience entries only if experienced
            $experienceEntries = [];
            if ($request->experience_type === 'experienced' && $request->has('experience_organization')) {
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

            // Determine active/inactive status relative to today's date
            $calculatedStatus = strtotime($request->joining_date) > strtotime(date('Y-m-d')) ? 'Inactive' : 'Active';

            // Clean up name parts to prevent duplication
            $allNames = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);
            $words = array_values(array_unique(array_filter(explode(' ', $allNames))));
            
            $cleanFirstName = $words[0] ?? $request->first_name;
            $cleanLastName = count($words) > 1 ? array_pop($words) : $request->last_name;
            $cleanMiddleName = count($words) > 1 ? implode(' ', array_slice($words, 1)) : null;

            // Create employee
            $employee = Employee::create([
                'employee_code' => $employeeCode,
                'first_name' => $cleanFirstName,
                'middle_name' => $cleanMiddleName,
                'last_name' => $cleanLastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'designation' => $request->designation,
                'department' => $request->department,
                'joining_date' => $request->joining_date,
                'employment_type' => $request->employment_type,
                'status' => $calculatedStatus,
                'basic_salary' => $request->basic_salary,
                'salary_type' => $request->salary_type,
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'IFSC_code' => $request->IFSC_code,
                'assigned_classes' => $request->has('assigned_classes') ? implode(', ', $request->assigned_classes) : null,
                'assigned_subjects' => $request->has('assigned_subjects') ? implode(', ', $request->assigned_subjects) : null,
                'education' => !empty($educationEntries) ? Employee::formatEducation($educationEntries) : null,
                'experience' => !empty($experienceEntries) ? Employee::formatExperience($experienceEntries) : null,
            ]);

            // Send employment confirmation email
            $this->sendEmploymentConfirmationEmail(
                $employee->first_name . ' ' . $employee->last_name, 
                $employee->email, 
                $employee->employee_code, 
                $employee->designation
            );

            // Create user account if designation is Teacher
            if (strtolower($request->designation) === 'teacher') {
                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'password' => null, // Will be set via setup link
                    'role' => 'teacher',
                ]);

                // Generate password setup link
                $token = Password::getRepository()->create($user);
                $setupUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

                // Send password setup email
                $this->sendTeacherPasswordSetupEmail(
                    $employee->first_name . ' ' . $employee->last_name, 
                    $employee->email, 
                    $setupUrl
                );
            }

            return redirect()
                ->route('employee.index')
                ->with('success', 'Employee added successfully.' . ($request->designation === 'Teacher' ? ' Teacher account created and password setup email sent.' : ''));

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
            'email' => 'required|email:rfc,dns|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z.-]+\.[A-Za-z]{2,}$/|unique:employees,email,' . $employee->id . '|max:255',
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
            'account_number' => 'required_if:payment_method,bank_transfer|string|regex:/^[0-9]{9,18}$/',
            'IFSC_code' => 'required_if:payment_method,bank_transfer|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/|max:11',
            
            // Education validation
            'education_degree.*' => 'nullable|string|max:255',
            'education_institution.*' => 'nullable|string|max:255',
            'education_year.*' => 'nullable|integer|min:1900|max:' . date('Y'),
            'education_grade.*' => 'nullable|string|max:50',
            
            // Experience Type Validation (for updates)
            'experience_type' => 'nullable|in:fresher,experienced',
            
            // Experience validation
            'experience_organization.*' => 'nullable|string|max:255',
            'experience_role.*' => 'nullable|string|max:255',
            'experience_start_date.*' => 'nullable|date',
            'experience_end_date.*' => 'nullable|date',
            'experience_total.*' => 'nullable|string|max:100',
            
            // Academic assignment validation
            'assigned_classes' => 'nullable|array|min:1',
            'assigned_classes.*' => 'nullable|string|max:50',
            'assigned_subjects' => 'nullable|array|min:1',
            'assigned_subjects.*' => 'nullable|string|max:50',
        ], [
            'phone.required' => 'Mobile number is required.',
            'phone.regex' => 'Mobile number must be exactly 10 digits.',
            'phone.max' => 'Mobile number must be exactly 10 digits.',
            'account_number.regex' => 'Account number must be between 9 and 18 digits.',
            'IFSC_code.regex' => 'Please enter a valid IFSC code.',
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

            // Process experience entries only if experienced
            $experienceEntries = [];
            if ($request->experience_type === 'experienced' && $request->has('experience_organization')) {
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

            // Clean up name parts to prevent duplication
            $allNames = trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);
            $words = array_values(array_unique(array_filter(explode(' ', $allNames))));
            
            $cleanFirstName = $words[0] ?? $request->first_name;
            $cleanLastName = count($words) > 1 ? array_pop($words) : $request->last_name;
            $cleanMiddleName = count($words) > 1 ? implode(' ', array_slice($words, 1)) : null;

            // Update employee
            $employee->update([
                'first_name' => $cleanFirstName,
                'middle_name' => $cleanMiddleName,
                'last_name' => $cleanLastName,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'blood_group' => $request->blood_group,
                'medical_conditions' => $request->medical_conditions,
                'joining_date' => $request->joining_date,
                'employment_type' => $request->employment_type,
                'department' => $request->department,
                'designation' => $request->designation,
                'employment_status' => $request->employment_status,
                'salary_mode' => $request->salary_mode,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'IFSC_code' => $request->IFSC_code,
                'assigned_classes' => $request->has('assigned_classes') ? implode(', ', $request->assigned_classes) : null,
                'assigned_subjects' => $request->has('assigned_subjects') ? implode(', ', $request->assigned_subjects) : null,
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

    /**
     * Fetch attendance calendar data for an employee.
     */
    public function attendanceCalendar(Employee $employee, Request $request)
    {
        try {
            $month = (int) $request->get('month', date('n'));
            $year = (int) $request->get('year', date('Y'));

            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            // 1. Get Attendance Records
            $attendance = AttendanceRecord::where('employee_code', $employee->employee_code)
                ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get()
                ->keyBy('attendance_date');

            // 2. Get Approved Leaves
            $leaves = LeaveRequest::where('employee_code', $employee->employee_code)
                ->where('status', 'approved')
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhereBetween('end_date', [$startDate->toDateString(), $endDate->toDateString()])
                      ->orWhere(function($sq) use ($startDate, $endDate) {
                          $sq->where('start_date', '<=', $startDate->toDateString())
                             ->where('end_date', '>=', $endDate->toDateString());
                      });
                })
                ->get();

            // 3. Get Holidays
            $holidays = Holiday::whereBetween('holiday_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->get()
                ->keyBy('holiday_date');

            // 4. Process Calendar Data
            $calendar = [];
            $counts = [
                'present' => 0,
                'absent' => 0,
                'leave' => 0,
                'holiday' => 0
            ];

            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                $dateStr = $date->toDateString();
                $status = 'none';
                $title = '';

                if ($holidays->has($dateStr)) {
                    $status = 'holiday';
                    $title = $holidays[$dateStr]->reason;
                    $counts['holiday']++;
                } else {
                    // Check Leave
                    $onLeave = $leaves->filter(function($l) use ($dateStr) {
                        return $dateStr >= $l->start_date && $dateStr <= $l->end_date;
                    })->first();

                    if ($onLeave) {
                        $status = 'leave';
                        $title = 'Leave: ' . $onLeave->reason;
                        $counts['leave']++;
                    } elseif ($attendance->has($dateStr)) {
                        $status = $attendance[$dateStr]->status;
                        $title = ucfirst($status);
                        if ($status == 'present') $counts['present']++;
                        if ($status == 'absent') $counts['absent']++;
                    }
                }

                $calendar[$dateStr] = [
                    'status' => $status,
                    'title' => $title
                ];
            }

            return response()->json([
                'success' => true,
                'employee' => $employee->full_name,
                'month' => $month,
                'year' => $year,
                'counts' => $counts,
                'calendar' => $calendar
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching calendar: ' . $e->getMessage()
            ], 500);
        }
    }
}