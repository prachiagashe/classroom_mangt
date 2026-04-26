<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
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
        // Dummy Assigned Classes
        $assignedClasses = [
            ['class' => '10', 'section' => 'A', 'subject' => 'Physics'],
            ['class' => '12', 'section' => 'B', 'subject' => 'Physics'],
            ['class' => '11', 'section' => 'C', 'subject' => 'Chemistry'],
        ];

        // Dummy Tasks - convert to collection
        $tasks = collect([
            ['title' => 'Prepare Unit Test Paper', 'deadline' => '2026-02-28', 'status' => 'pending', 'priority' => 'high'],
            ['title' => 'Submit Attendance Report', 'deadline' => '2026-02-25', 'status' => 'completed', 'priority' => 'medium'],
            ['title' => 'Grade Physics Assignments', 'deadline' => '2026-02-26', 'status' => 'pending', 'priority' => 'medium'],
            ['title' => 'Update Lesson Plans', 'deadline' => '2026-02-24', 'status' => 'completed', 'priority' => 'low'],
        ]);

        // Dummy Upcoming Classes
        $todayClasses = [
            ['class' => '10-A', 'subject' => 'Physics', 'time' => '10:00 AM - 11:00 AM', 'room' => 'Lab 1'],
            ['class' => '12-B', 'subject' => 'Physics', 'time' => '2:00 PM - 3:00 PM', 'room' => 'Room 205'],
        ];

        $tomorrowClasses = [
            ['class' => '11-C', 'subject' => 'Chemistry', 'time' => '9:00 AM - 10:00 AM', 'room' => 'Lab 2'],
            ['class' => '10-A', 'subject' => 'Physics', 'time' => '11:00 AM - 12:00 PM', 'room' => 'Room 101'],
        ];

        $dayAfterClasses = [
            ['class' => '12-B', 'subject' => 'Physics', 'time' => '10:00 AM - 11:00 AM', 'room' => 'Room 205'],
        ];

        return view('teacher.dashboard', compact(
            'assignedClasses',
            'tasks',
            'todayClasses',
            'tomorrowClasses',
            'dayAfterClasses'
        ));
    }

    /**
     * Show create assignment form.
     */
    public function createAssignment()
    {
        // Dummy data for form
        $classes = ['10', '11', '12'];
        $sections = ['A', 'B', 'C'];
        $subjects = ['Physics', 'Chemistry', 'Mathematics', 'Biology'];
        
        return view('teacher.assignments.create', compact('classes', 'sections', 'subjects'));
    }

    /**
     * Store assignment (dummy implementation).
     */
    public function storeAssignment(Request $request)
    {
        // Dummy implementation - just return success
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
        
        return view('teacher.assignments.assignment', compact('employee'));
    }
}
