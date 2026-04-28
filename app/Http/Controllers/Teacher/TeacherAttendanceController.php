<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TeacherAttendanceController extends Controller
{
    /**
     * Mark teacher's attendance for today
     */
    public function markAttendance(Request $request)
    {
        return redirect()->back()->with('error', 'You are not allowed to directly mark attendance. Attendance is managed by Admin.');
    }
    
    /**
     * Get attendance status for today (AJAX endpoint)
     */
    public function getTodayAttendanceStatus()
    {
        try {
            $teacher = Employee::where('email', Auth::user()->email)->first();
            
            if (!$teacher) {
                return response()->json(['error' => 'Teacher not found'], 404);
            }
            
            $today = now()->toDateString();
            
            // Check session first for immediate response
            $sessionData = Session::get('teacher_attendance_' . $teacher->employee_code . '_' . $today);
            
            if ($sessionData) {
                return response()->json([
                    'marked' => true,
                    'status' => $sessionData['status'],
                    'time' => $sessionData['time']
                ]);
            }
            
            // Check database
            $attendance = AttendanceRecord::where('employee_code', $teacher->employee_code)
                ->where('attendance_date', $today)
                ->first();
                
            return response()->json([
                'marked' => $attendance ? true : false,
                'status' => $attendance ? $attendance->status : null,
                'time' => $attendance ? $attendance->created_at->format('H:i:s') : null
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get attendance status'], 500);
        }
    }
}
