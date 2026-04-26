<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admission;
use App\Models\StudentAttendence;

class AttendanceController extends Controller
{
    public function index()
    {
        $classes = ['Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'Class 11', 'Class 12'];
        $classCounts = [];

        foreach ($classes as $className) {
            $classNumber = $this->extractClassNumber($className);
            if ($classNumber) {
                // Fetch count using flexible matching for the class number
                $classCounts[$className] = Admission::where('class', 'LIKE', "%$classNumber%")
                    ->get()
                    ->filter(function($admission) use ($classNumber) {
                        return $this->extractClassNumber($admission->class) === $classNumber;
                    })->count();
            } else {
                $classCounts[$className] = 0;
            }
        }

        return view('admin.attendance.index', compact('classes', 'classCounts'));
    }

    public function showClass($class, Request $request)
    {
        $classNumber = $this->extractClassNumber($class);
        $students = Admission::where('class', 'LIKE', "%$classNumber%")
            ->get()
            ->filter(function($admission) use ($classNumber) {
                return $this->extractClassNumber($admission->class) === $classNumber;
            })
            ->sortBy('roll_number');
            
        // Handle month filtering
        $monthFilter = $request->get('month');
        if ($monthFilter) {
            $selectedDate = Carbon::createFromFormat('Y-m', $monthFilter);
        } else {
            $selectedDate = Carbon::now();
        }
        
        $today = Carbon::now();
        $dayNum = $today->day;
        $monthYear = $selectedDate->format('F Y');
        $daysInMonth = $selectedDate->daysInMonth;
        
        // Get existing attendance for today if any (only for current month)
        if ($selectedDate->format('Y-m') === $today->format('Y-m')) {
            $existingAttendance = StudentAttendence::whereIn('roll_no', $students->pluck('roll_number'))
                ->where('month', $monthYear)
                ->get()
                ->pluck("day_{$dayNum}", 'roll_no');
        } else {
            $existingAttendance = collect(); // Empty collection for non-current months
        }

        // Get all attendance records for the selected month (for the calendar table)
        $monthlyAttendance = StudentAttendence::whereIn('roll_no', $students->pluck('roll_number'))
            ->where('month', $monthYear)
            ->get()
            ->keyBy('roll_no');

        return view('admin.attendance.show', compact(
            'students', 
            'class', 
            'existingAttendance', 
            'today', 
            'monthlyAttendance', 
            'daysInMonth',
            'monthYear',
            'selectedDate'
        ));
    }

    public function saveAttendance(Request $request)
    {
        $class = $request->input('class');
        $classNumber = $this->extractClassNumber($class);
        $selectedRolls = $request->input('attendance', []); // Rolls marked as Present

        $students = Admission::where('class', 'LIKE', "%$classNumber%")
            ->get()
            ->filter(function($admission) use ($classNumber) {
                return $this->extractClassNumber($admission->class) === $classNumber;
            });
        
        $today = Carbon::now();
        $dayNum = $today->day;
        $monthYear = $today->format('F Y');
        $dayColumn = "day_{$dayNum}";

        foreach ($students as $student) {
            $status = in_array($student->roll_number, $selectedRolls) ? 'P' : 'A';
            
            $attendance = StudentAttendence::where('roll_no', $student->roll_number)
                ->where('month', $monthYear)
                ->first();

            if ($attendance) {
                $attendance->$dayColumn = $status;
                $attendance->calculateStatistics(); // Assuming Model has this method as seen before
                $attendance->save();
            } else {
                $attendanceData = [
                    'roll_no' => $student->roll_number,
                    'name' => $student->student_name,
                    'month' => $monthYear,
                    $dayColumn => $status
                ];
                $newAttendance = StudentAttendence::create($attendanceData);
                $newAttendance->calculateStatistics();
            }
        }

        return redirect()->back()->with('success', "Attendance for $class saved successfully for " . $today->format('d M Y'));
    }

    public function monthlyRegister(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $selectedDate = Carbon::create($year, $month, 1);
        $daysInMonth = $selectedDate->daysInMonth;
        $monthName = $selectedDate->format('F');
        
        $students = Admission::orderBy('roll_number')->get();
        // ... rest of register logic ...
    }
    
    public function exportExcel(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        return response()->json([
            'message' => 'Excel export functionality to be implemented',
            'month' => $month,
            'year' => $year
        ]);
    }
    
    public function markAttendance(Request $request)
    {
        $rollNo = $request->input('roll_no');
        $name = $request->input('name');
        $month = $request->input('month');
        $day = $request->input('day');
        $status = $request->input('status');
        
        // Validate input
        if (!$rollNo || !$month || !$day || !$status) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }
        
        if (!in_array($status, ['P', 'A'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }
        
        // Get or create attendance record
        $attendance = StudentAttendence::where('roll_no', $rollNo)
            ->where('month', $month)
            ->first();
            
        if (!$attendance) {
            $attendance = StudentAttendence::create([
                'roll_no' => $rollNo,
                'name' => $name,
                'month' => $month
            ]);
        }
        
        // Mark attendance for the specific day
        $dayColumn = "day_{$day}";
        $attendance->$dayColumn = $status;
        $attendance->calculateStatistics();
        $attendance->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'attendance' => $attendance
        ]);
    }
    /**
     * Helper to extract numeric part from class name.
     */
    private function extractClassNumber($className)
    {
        if (!$className) return null;
        if (preg_match('/(\d+)/', $className, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
