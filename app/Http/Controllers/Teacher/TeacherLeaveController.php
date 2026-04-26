<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Notification;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherLeaveController extends Controller
{
    /**
     * Display teacher's leave requests.
     */
    public function index()
    {
        $teacher = Employee::where('email', Auth::user()->email)->firstOrFail();
        $leaveRequests = LeaveRequest::where('employee_code', $teacher->employee_code)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teacher.leave.index', compact('leaveRequests'));
    }

    /**
     * Show leave request creation form.
     */
    public function create()
    {
        return view('teacher.leave.create');
    }

    /**
     * Store a new leave request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:casual,sick,emergency',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $teacher = Employee::where('email', Auth::user()->email)->firstOrFail();

        $leaveData = [
            'employee_code' => $teacher->employee_code,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending'
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('attachments', $filename, 'public');
            $leaveData['attachment'] = $filename;
        }

        $leaveRequest = LeaveRequest::create($leaveData);

        // Create notifications for all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'New Leave Request',
                'message' => "{$teacher->full_name} requested leave from {$validated['start_date']} to {$validated['end_date']}",
                'type' => 'leave_request',
                'data' => [
                    'leave_request_id' => $leaveRequest->id,
                    'teacher_name' => $teacher->full_name,
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date']
                ],
                'is_read' => false
            ]);
        }

        return redirect()->route('teacher.leaves.index')
            ->with('success', 'Leave request sent to admin successfully.');
    }
}
