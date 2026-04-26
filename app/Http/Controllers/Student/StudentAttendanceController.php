<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admission;
use App\Models\StudentAttendence;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $student = Admission::where('email', auth()->user()->email)->first();
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found. Please contact administrator.');
        }
        
        $currentMonth = Carbon::now()->format('F Y');
        
        // Get or create attendance record for current month
        $attendance = StudentAttendence::where('roll_no', $student->roll_number)
            ->where('month', $currentMonth)
            ->first();
            
        if (!$attendance) {
            $attendance = StudentAttendence::create([
                'roll_no' => $student->roll_number,
                'name' => $student->student_name,
                'month' => $currentMonth
            ]);
        }
        
        // Automatically mark previous days as absent if not marked
        $this->markPreviousDaysAsAbsent($attendance);
        
        // Get all attendance records for detailed view
        $attendances = StudentAttendence::where('roll_no', $student->roll_number)
            ->orderBy('month', 'desc')
            ->get();
            
        return view('students.attendance', compact('student', 'attendance', 'attendances'));
    }
    
    private function markPreviousDaysAsAbsent($attendance)
    {
        $currentDay = Carbon::now()->day;
        
        // Mark all previous days as absent if not already marked
        for ($day = 1; $day < $currentDay; $day++) {
            $dayColumn = "day_{$day}";
            
            // Only mark as absent if not already marked (null)
            if ($attendance->$dayColumn === null) {
                // Check if it's not a Sunday or holiday
                $dayDate = Carbon::parse($day . " " . $attendance->month . " 2026");
                
                if (!$dayDate->isSunday() && !$this->isHoliday($dayDate)) {
                    $attendance->$dayColumn = 'A';
                }
            }
        }
        
        $attendance->calculateStatistics();
        $attendance->save();
    }
    
    private function isHoliday($date)
    {
        $holidays = [
            '2026-01-26' => 'Republic Day',
            '2026-08-15' => 'Independence Day',
            '2026-10-02' => 'Gandhi Jayanti',
            '2026-12-25' => 'Christmas',
        ];
        
        return isset($holidays[$date->format('Y-m-d')]);
    }
    
    public function markAttendance(Request $request)
    {
        $student = Admission::where('email', auth()->user()->email)->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student record not found'], 404);
        }
        
        $currentMonth = Carbon::now()->format('F Y');
        $currentDay = Carbon::now()->day;
        
        $attendance = StudentAttendence::where('roll_no', $student->roll_number)
            ->where('month', $currentMonth)
            ->first();
            
        if (!$attendance) {
            $attendance = StudentAttendence::create([
                'roll_no' => $student->roll_number,
                'name' => $student->student_name,
                'month' => $currentMonth
            ]);
        }
        
        $dayColumn = "day_{$currentDay}";
        $status = $request->input('status'); // Only 'P' for present
        
        // Only allow marking present for current day
        if ($status === 'P') {
            $attendance->$dayColumn = 'P';
            $attendance->calculateStatistics();
            $attendance->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked as present successfully',
                'attendance' => $attendance
            ]);
        }
        
        return response()->json(['error' => 'Invalid status'], 400);
    }
    
    public function studentProgress($id)
    {
        $student = Admission::findOrFail($id);
        
        // Get current month attendance
        $currentMonth = Carbon::now()->format('F Y');
        
        $attendance = StudentAttendence::where('roll_no', $student->roll_number)
            ->where('month', $currentMonth)
            ->first();
            
        $presentDays = 0;
        $absentDays = 0;
        $attendancePercentage = 0;
        
        if ($attendance) {
            $presentDays = $attendance->total_p;
            $absentDays = $attendance->total_a;
            $attendancePercentage = $attendance->percentage;
        }
        
        // Get last 3 months trend
        $monthlyTrend = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('F Y');
            
            $monthAttendance = StudentAttendence::where('roll_no', $student->roll_number)
                ->where('month', $monthName)
                ->first();
                
            $monthPercentage = 0;
            if ($monthAttendance) {
                $monthPercentage = $monthAttendance->percentage;
            }
            
            $monthlyTrend[] = [
                'month' => $date->format('F'),
                'percentage' => $monthPercentage
            ];
        }
        
        // Get attendance events for calendar (current year)
        $attendanceEvents = [];
        $attendances = StudentAttendence::where('roll_no', $student->roll_number)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
            
        foreach ($attendances as $att) {
            // Parse month and create events for each day
            for ($day = 1; $day <= 31; $day++) {
                $dayColumn = "day_{$day}";
                $status = $att->$dayColumn;
                
                if ($status) {
                    // Parse the month to get date - fix the date format
                    try {
                        $monthDate = Carbon::parse($att->month . ' ' . $day . ', 2026');
                        $attendanceEvents[] = [
                            'title' => ucfirst($status),
                            'start' => $monthDate->format('Y-m-d'),
                            'backgroundColor' => $status === 'P' ? '#22c55e' : '#ef4444',
                            'borderColor' => $status === 'P' ? '#22c55e' : '#ef4444',
                            'textColor' => '#ffffff',
                            'display' => 'background'
                        ];
                    } catch (\Exception $e) {
                        // Skip invalid dates
                        continue;
                    }
                }
            }
        }
        
        // Subject-wise attendance (dummy data for now)
        $subjectAttendance = [
            ['subject' => 'Mathematics', 'percentage' => 96],
            ['subject' => 'Physics', 'percentage' => 93],
            ['subject' => 'Chemistry', 'percentage' => 95],
            ['subject' => 'Computer Science', 'percentage' => 98]
        ];
        
        return view('enquiry.admissions.track-attendence', compact(
            'student',
            'presentDays',
            'absentDays',
            'attendancePercentage',
            'monthlyTrend',
            'attendanceEvents',
            'subjectAttendance'
        ));
    }
}
