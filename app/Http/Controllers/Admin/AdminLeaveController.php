<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\AttendanceRecord;
use App\Models\Notification;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Admission;
use App\Models\Employee\Employee;
use App\Traits\SendsEmployeeEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    use SendsEmployeeEmails;
    /**
     * Display all leave requests and daily attendance for admin.
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::with('employee');

        if ($request->filled('leave_id')) {
            $query->orderByRaw('id = ? desc', [$request->get('leave_id')]);
        }
        
        $query->orderBy('created_at', 'desc');

        // Filter: Default to active (last 20 days or pending)
        $leaveFilter = $request->get('leave_filter', 'active');
        $twentyDaysAgo = now()->subDays(20)->toDateString();

        if ($leaveFilter === 'active') {
            $query->where(function ($q) use ($twentyDaysAgo, $request) {
                $q->where('created_at', '>=', $twentyDaysAgo)
                  ->orWhere('status', 'pending');
                if ($request->filled('leave_id')) {
                    $q->orWhere('id', $request->get('leave_id'));
                }
            });
        } elseif ($leaveFilter === 'archived') {
            $query->where('created_at', '<', $twentyDaysAgo)
                  ->where('status', '!=', 'pending');
        }

        if ($request->filled('status_filter') && $request->get('status_filter') !== 'all') {
            $query->where('status', $request->get('status_filter'));
        }

        if ($request->filled('quick_filter')) {
            $quick = $request->get('quick_filter');
            if ($quick === 'today') {
                $query->whereDate('created_at', date('Y-m-d'));
            } elseif ($quick === 'this-week') {
                $query->whereBetween('created_at', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            } elseif ($quick === 'emergency') {
                $query->where(function($q) {
                    $q->where('reason', 'like', '%emergency%')
                      ->orWhere('leave_type', 'like', '%emergency%');
                });
            }
        }

        // Search by employee
        if ($request->filled('search_employee')) {
            $search = $request->get('search_employee');
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $leaveRequests = $query->paginate(10)->withQueryString();

        // Summary Statistics (Global)
        $totalRequests = LeaveRequest::count();
        $pendingCount = LeaveRequest::where('status', 'pending')->count();
        $approvedCount = LeaveRequest::where('status', 'approved')->count();
        $rejectedCount = LeaveRequest::where('status', 'rejected')->count();

        // For Teacher Attendance Tab
        $date = $request->get('date', date('Y-m-d'));
        
        // Get all employees (teachers and staff)
        $employees = \App\Models\Employee\Employee::orderBy('first_name')->get();
        
        // Get attendance records for the selected date
        $attendanceRecords = AttendanceRecord::where('attendance_date', $date)
            ->get()
            ->keyBy('employee_code');

        // Get approved leave requests overlapping with selected date
        $onLeaveToday = LeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->pluck('employee_code')
            ->toArray();

        $holidays = Holiday::all();
        $todayHoliday = $holidays->where('holiday_date', $date)->first();
 
        return view('admin.leave.index', compact(
            'leaveRequests', 
            'employees', 
            'attendanceRecords', 
            'date', 
            'onLeaveToday',
            'totalRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'holidays',
            'todayHoliday'
        ));
    }

    /**
     * Bulk approve leave requests.
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No requests selected.']);
        }

        $requests = LeaveRequest::whereIn('id', $ids)->where('status', 'pending')->get();
        foreach ($requests as $leaveRequest) {
            $leaveRequest->update([
                'status' => 'approved',
                'admin_remark' => 'Bulk approved by admin'
            ]);

            // Insert attendance records for leave days
            $start = \Carbon\Carbon::parse($leaveRequest->start_date);
            $end = \Carbon\Carbon::parse($leaveRequest->end_date);
            
            while ($start->lte($end)) {
                AttendanceRecord::updateOrCreate(
                    [
                        'employee_code' => $leaveRequest->employee_code,
                        'attendance_date' => $start->toDateString(),
                    ],
                    [
                        'status' => 'leave'
                    ]
                );
                $start->addDay();
            }

            // Notify teacher
            $this->notifyTeacher($leaveRequest, 'approved');
        }

        return response()->json(['success' => true, 'message' => count($requests) . ' leave requests approved successfully.']);
    }

    /**
     * Bulk reject leave requests.
     */
    public function bulkReject(Request $request)
    {
        $ids = $request->input('ids', []);
        $remark = $request->input('admin_remark', 'Rejected by admin');
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No requests selected.']);
        }

        $requests = LeaveRequest::whereIn('id', $ids)->where('status', 'pending')->get();
        foreach ($requests as $leaveRequest) {
            $leaveRequest->update([
                'status' => 'rejected',
                'admin_remark' => 'Bulk rejected: ' . $remark
            ]);

            // Notify teacher
            $this->notifyTeacher($leaveRequest, 'rejected');
        }

        return response()->json(['success' => true, 'message' => count($requests) . ' leave requests rejected successfully.']);
    }

    /**
     * Internal helper to notify teacher about leave status.
     */
    private function notifyTeacher($leaveRequest, $status)
    {
        $employee = $leaveRequest->employee;
        $user = User::where('email', $employee->email)->first();
        
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Leave Request ' . ucfirst($status),
                'message' => "Your leave request for {$leaveRequest->start_date} has been {$status}.",
                'type' => 'leave_status',
                'data' => [
                    'leave_request_id' => $leaveRequest->id,
                    'status' => $status,
                    'start_date' => $leaveRequest->start_date
                ],
                'is_read' => false
            ]);

            // Send Email
            $this->sendLeaveStatusEmail($employee->full_name, $employee->email, $status, $leaveRequest->start_date);
        }
    }

    /**
     * Mark single attendance via AJAX.
     */
    public function markSingleAttendance(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|exists:employees,employee_code',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        // 1. Check for Holiday
        $isHoliday = Holiday::where('holiday_date', $request->date)->exists();
        if ($isHoliday) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark attendance on a public holiday.'
            ], 422);
        }

        // 2. Check for Approved Leave
        $onLeave = LeaveRequest::where('employee_code', $request->employee_code)
            ->where('status', 'approved')
            ->where('start_date', '<=', $request->date)
            ->where('end_date', '>=', $request->date)
            ->exists();
        
        if ($onLeave) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark attendance as employee is on approved leave.'
            ], 422);
        }

        $attendance = AttendanceRecord::updateOrCreate(
            [
                'employee_code' => $request->employee_code,
                'attendance_date' => $request->date,
            ],
            [
                'status' => $request->status
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked as ' . $request->status,
            'status' => $request->status
        ]);
    }

    /**
     * Save/Update daily attendance for all employees.
     */
    public function saveAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $date = $request->date;
        $attendanceData = $request->attendance;

        // 1. Check for Holiday
        $isHoliday = Holiday::where('holiday_date', $date)->exists();
        if ($isHoliday) {
            return redirect()->back()->with('error', 'Cannot save attendance: ' . $date . ' is a declared holiday.');
        }

        foreach ($attendanceData as $employeeCode => $status) {
            // 2. Check for Approved Leave for each employee
            $onLeave = LeaveRequest::where('employee_code', $employeeCode)
                ->where('status', 'approved')
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->exists();

            if ($onLeave) {
                $status = 'leave';
            }

            AttendanceRecord::updateOrCreate(
                [
                    'employee_code' => $employeeCode,
                    'attendance_date' => $date,
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->route('admin.leave.index', ['date' => $date])
            ->with('success', 'Attendance records for ' . $date . ' have been saved successfully.');
    }

    /**
     * Display the leave management dashboard.
     */
    public function leaveIndex()
    {
        return view('employee.leave.index');
    }

    /**
     * Show attendance by date for admin calendar view.
     */
    public function attendanceByDate($date)
    {
        // Get actual attendance records from database with employee relationships
        $attendanceRecords = AttendanceRecord::with('employee')
            ->where('attendance_date', $date)
            ->get();
        
        // Get all employees from employees table (all are assumed to be teachers)
        $teachers = \App\Models\Employee\Employee::orderBy('first_name')
            ->get();
        
        // Create attendance records array for all teachers
        $attendanceData = [];
        $teacherNames = [];
        
        // Add actual attendance records from database
        foreach ($attendanceRecords as $record) {
            if ($record->employee) {
                $fullName = $record->employee->full_name;
                $attendanceData[$fullName] = $record->status;
                $teacherNames[] = $fullName;
            }
        }
        
        // Add all teachers with 'not_marked' status
        foreach ($teachers as $teacher) {
            $fullName = $teacher->full_name;
            if (!isset($attendanceData[$fullName])) {
                $attendanceData[$fullName] = 'not_marked';
            }
            $teacherNames[] = $fullName;
        }
        
        // Sort teacher names alphabetically
        $teacherNames = array_unique($teacherNames);
        sort($teacherNames);
        
        return view('admin.attendance-date', compact('teacherNames', 'attendanceData', 'date'));
    }

    /**
     * Approve a leave request.
     */
    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update([
            'status' => 'approved',
            'admin_remark' => 'Approved by admin'
        ]);

        // Insert attendance records for leave days
        $start = \Carbon\Carbon::parse($leaveRequest->start_date);
        $end = \Carbon\Carbon::parse($leaveRequest->end_date);
        
        while ($start->lte($end)) {
            AttendanceRecord::updateOrCreate(
                [
                    'employee_code' => $leaveRequest->employee_code,
                    'attendance_date' => $start->toDateString(),
                ],
                [
                    'status' => 'leave'
                ]
            );
            $start->addDay();
        }

        // Notify teacher
        $this->notifyTeacher($leaveRequest, 'approved');

        return redirect()->route('admin.leave.index')
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject a leave request with remark.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_remark' => 'required|string|max:500'
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update([
            'status' => 'rejected',
            'admin_remark' => 'Rejected: ' . $validated['admin_remark']
        ]);

        // Notify teacher
        $this->notifyTeacher($leaveRequest, 'rejected');

        return redirect()->route('admin.leave.index')
            ->with('success', 'Leave request rejected successfully.');
    }

    /**
     * Declare a holiday for all teachers and students.
     */
    public function declareHoliday(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'reason' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Save Holiday record
            Holiday::updateOrCreate(
                ['holiday_date' => $request->holiday_date],
                ['reason' => $request->reason]
            );

            // Note: We no longer mark individual attendance records as 'absent' for holidays,
            // as the system now dynamically checks the holiday table.

            DB::commit();

            // 4. Create Notification for all users (Teachers + Students) except current admin
            $users = User::where('id', '!=', Auth::id())->get();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'sender_id' => Auth::id(),
                    'title' => 'Holiday Declared',
                    'message' => "Holiday on {$request->holiday_date} - {$request->reason}",
                    'type' => 'holiday',
                    'data' => [
                        'holiday_date' => $request->holiday_date,
                        'reason' => $request->reason,
                        'redirect_url' => $user->role === 'admin' 
                            ? route('admin.leave.index', ['tab' => 'attendance', 'date' => $request->holiday_date])
                            : ($user->role === 'teacher' ? '/teacher/attendance' : '/student/timetable')
                    ],
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Holiday declared successfully for ' . $request->holiday_date
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error declaring holiday: ' . $e->getMessage()
            ], 500);
        }
    }
}
