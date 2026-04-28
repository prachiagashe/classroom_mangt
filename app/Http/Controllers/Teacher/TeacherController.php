<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Holiday;
use App\Models\Employee\Employee;
use App\Models\SalaryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display teacher dashboard.
     */
    public function dashboard()
    {
        $employee = Employee::where('email', Auth::user()->email)->firstOrFail();
        
        $assignedClassesString = $employee->assigned_classes ?? '';
        $classNames = array_filter(array_map('trim', explode(',', $assignedClassesString)));
        
        $assignedClasses = [];
        foreach ($classNames as $className) {
            $studentCount = \App\Models\Admission::where('class', $className)
                ->orWhere('class', 'like', $className . '-%')
                ->count();
                
            $studentRolls = \App\Models\Admission::where('class', $className)
                ->orWhere('class', 'like', $className . '-%')
                ->pluck('roll_number');
                
            $today = now();
            $dayNum = $today->day;
            $monthYear = $today->format('F Y');
            $dayColumn = "day_{$dayNum}";
            
            $presentStudents = \App\Models\StudentAttendence::whereIn('roll_no', $studentRolls)
                ->where('month', $monthYear)
                ->where($dayColumn, 'P')
                ->count();
            
            $assignedClasses[] = [
                'name' => $className,
                'student_count' => $studentCount,
                'present_students' => $presentStudents,
            ];
        }

        $classes = \App\Models\Subject::distinct()->pluck('class_name')->toArray();
        if (empty($classes)) {
            $classes = ['10', '11', '12'];
        }
        $sections = ['A', 'B', 'C'];
        $subjects = \App\Models\Subject::distinct()->pluck('name')->toArray();
        if (empty($subjects)) {
            $subjects = ['Physics', 'Chemistry', 'Mathematics', 'Biology'];
        }

        $teacherRecords = AttendanceRecord::where('employee_code', $employee->employee_code)->get();
        $holidays = Holiday::all();

        return view('teacher.dashboard', compact(
            'assignedClasses',
            'classes',
            'sections',
            'subjects',
            'employee',
            'teacherRecords',
            'holidays'
        ));
    }

    public function classDetail($className)
    {
        $employee = Employee::where('email', Auth::user()->email)->firstOrFail();
        
        $students = \App\Models\Admission::where('class', $className)
            ->orWhere('class', 'like', $className . '-%')
            ->get();
            
        $studentEmails = $students->pluck('email')->toArray();
        $users = \App\Models\User::whereIn('email', $studentEmails)->get()->keyBy('email');
        
        foreach ($students as $student) {
            $student->user_id = optional($users->get($student->email))->id;
        }

        $assignments = \App\Models\Assignment::where('class_id', $className)
            ->where('teacher_id', auth()->id())
            ->with('submissions')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $subjects = \App\Models\Subject::distinct()->pluck('name')->toArray();

        return view('teacher.classes.detail', compact('className', 'students', 'assignments', 'subjects', 'employee'));
    }

    /**
     * Show create assignment form.
     */
    public function createAssignment()
    {
        // Dynamic data for form
        $classes = \App\Models\Subject::distinct()->pluck('class_name')->toArray();
        if (empty($classes)) {
            $classes = ['10', '11', '12'];
        }
        $sections = ['A', 'B', 'C'];
        $subjects = \App\Models\Subject::distinct()->pluck('name')->toArray();
        if (empty($subjects)) {
            $subjects = ['Physics', 'Chemistry', 'Mathematics', 'Biology'];
        }
        
        return view('teacher.assignments.create', compact('classes', 'sections', 'subjects'));
    }

    /**
     * Store assignment.
     */
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'subject' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'required|file|mimes:pdf|max:10240', // Mandatory PDF
            'due_date' => 'required|date',
        ]);

        $pdfPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $pdfPath = $file->storeAs('assignments', $fileName, 'public');
        }

        $assignment = \App\Models\Assignment::create([
            'teacher_id' => auth()->id(),
            'class_id' => $request->class,
            'subject' => $request->subject,
            'title' => $request->title,
            'description' => $request->description,
            'pdf_path' => $pdfPath,
            'due_date' => $request->due_date,
        ]);

        // Find students of the class
        $studentEmails = \App\Models\Admission::where('class', $request->class)
            ->orWhere('class', 'like', $request->class . '-%')
            ->pluck('email')
            ->unique();

        $users = \App\Models\User::whereIn('email', $studentEmails)->get();

        foreach ($users as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'sender_id' => auth()->id(),
                'title' => 'New Assignment Uploaded',
                'message' => "New assignment: '{$request->title}' for subject: '{$request->subject}'. Due on " . \Carbon\Carbon::parse($request->due_date)->format('M d, Y'),
                'type' => 'assignment',
                'data' => [
                    'redirect_url' => route('student.assignments'),
                    'assignment_id' => $assignment->id
                ]
            ]);
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Assignment created successfully!');
    }

    /**
     * Mark teacher attendance for today.
     */
    public function markAttendance(Request $request)
    {
        $teacher = Employee::where('email', Auth::user()->email)->firstOrFail();
        $today = now()->toDateString();
        
        // Check if already marked for today
        $existingRecord = AttendanceRecord::where('employee_code', $teacher->employee_code)
            ->where('attendance_date', $today)
            ->first();
        
        if (!$existingRecord) {
            // Create new attendance record
            AttendanceRecord::create([
                'employee_code' => $teacher->employee_code,
                'attendance_date' => $today,
                'status' => 'present'
            ]);
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Attendance marked successfully!');
    }

    /**
     * Display teacher profile.
     */
    public function profile()
    {
        $employee = Employee::where('email', Auth::user()->email)->firstOrFail();
        return view('teacher.profile', compact('employee'));
    }

    /**
     * Display teacher salary history.
     */
    public function salaryHistory()
    {
        $employee = Employee::where('email', Auth::user()->email)->firstOrFail();
        
        $salaryRecords = SalaryRecord::where('employee_code', $employee->employee_code)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('teacher.salary.history', compact('employee', 'salaryRecords'));
    }

    /**
     * Display teacher leave records.
     */
    public function leaveRecords()
    {
        $employee = Employee::where('email', Auth::user()->email)->firstOrFail();
        $leaveRequests = \App\Models\LeaveRequest::where('employee_code', $employee->employee_code)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teacher.leave.index', compact('leaveRequests'));
    }

    /**
     * Display teacher assigned classes and subjects.
     */
    public function assignments()
    {
        return redirect()->route('teacher.assignments.assignment');
    }

    /**
     * Display teacher schedule and assignments page.
     */
    public function assignment()
    {
        $employee = Employee::where('email', Auth::user()->email)->firstOrFail();
        
        $classes = \App\Models\Subject::distinct()->pluck('class_name')->toArray();
        if (empty($classes)) {
            $classes = ['10', '11', '12'];
        }
        $sections = ['A', 'B', 'C'];
        $subjects = \App\Models\Subject::distinct()->pluck('name')->toArray();
        if (empty($subjects)) {
            $subjects = ['Physics', 'Chemistry', 'Mathematics', 'Biology'];
        }

        // Fetch created assignments
        $createdAssignments = \App\Models\Assignment::where('teacher_id', auth()->id())
            ->with('submissions.student')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.assignments.assignment', compact('employee', 'classes', 'sections', 'subjects', 'createdAssignments'));
    }

    /**
     * Evaluate assignment submissions.
     */
    public function evaluateSubmission(Request $request, $id)
    {
        $request->validate([
            'marks' => 'required|string|max:255',
        ]);

        $submission = \App\Models\AssignmentSubmission::findOrFail($id);
        $submission->update([
            'status' => 'checked',
            'marks' => $request->marks
        ]);

        return redirect()->back()->with('success', 'Assignment evaluated successfully!');
    }

    public function destroyAssignment($id)
    {
        $assignment = \App\Models\Assignment::where('id', $id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();
            
        $assignment->submissions()->delete();
        $assignment->delete();
        
        return redirect()->back()->with('success', 'Assignment deleted successfully!');
    }
}
