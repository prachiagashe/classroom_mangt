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
        try {
            // Get current teacher
            $teacher = Employee::where('email', Auth::user()->email)->first();
            
            if (!$teacher) {
                return redirect()->back()->with('error', 'Teacher record not found.');
            }
            
            $today = now()->toDateString();
            
            // Check if attendance already marked
            $existing = AttendanceRecord::where('employee_code', $teacher->employee_code)
                ->where('attendance_date', $today)
                ->first();
                
            if ($existing) {
                return redirect()->back()->with('info', 'Attendance already marked for today.');
            }
            
            // Create attendance record
            AttendanceRecord::create([
                'employee_code' => $teacher->employee_code,
                'attendance_date' => $today,
                'status' => 'present',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Store in session for immediate feedback
            Session::put('teacher_attendance_' . $teacher->employee_code . '_' . $today, [
                'marked' => true,
                'status' => 'present',
                'time' => now()->format('H:i:s')
            ]);
            
            return redirect()->back()->with('success', 'Attendance marked successfully for today!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark attendance: ' . $e->getMessage());
        }
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
