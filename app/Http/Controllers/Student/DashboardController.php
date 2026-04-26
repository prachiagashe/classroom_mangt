<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Subject;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student's admission record
        $admission = Admission::where('email', $user->email)->first();
        
        // Get student's class details from admissions table
        $studentClass = $admission ? $admission->class : null;
        $studentName = $admission ? $admission->student_name : $user->name;
        $rollNumber = $admission ? $admission->roll_number : null;
        $admissionDate = $admission ? $admission->admission_date : null;
        
        // Extract class number for subject queries (handle different formats)
        $classNumber = $studentClass;
        if ($studentClass && preg_match('/(\d+)/', $studentClass, $matches)) {
            $classNumber = $matches[1];
        }
        
        // Sample stats - you can calculate these from your actual data
        $stats = [
            'total_subjects' => $classNumber ? Subject::where('class_name', $classNumber)->count() : 0,
            'attendance_rate' => 92, // Sample data
            'assignments_pending' => 3, // Sample data
            'upcoming_exams' => 2, // Sample data
            'total_courses' => 5,
            'completed_assignments' => 12,
            'pending_assignments' => 3,
            'attendance_percentage' => 85,
        ];

        // Calculate fee-related variables
        $totalFee = $admission ? $admission->total_fee : 0;
        $paidAmount = $admission ? $admission->paid_amount : 0;
        $pendingAmount = $admission ? ($totalFee - $paidAmount) : 0;
        $nextDueDate = $admission ? \Carbon\Carbon::parse($admission->admission_date)->addDays(30) : null;
        $isOverdue = $admission && $pendingAmount > 0 && \Carbon\Carbon::parse($admission->admission_date)->addDays(30)->isPast();
        $overdueDays = $admission && $isOverdue ? \Carbon\Carbon::parse($admission->admission_date)->diffInDays(\Carbon\Carbon::now()) : 0;
        $isDueSoon = $admission && !$isOverdue && $pendingAmount > 0;
        $paymentStatus = $admission ? ($pendingAmount > 0 ? 'pending' : 'paid') : 'not_admitted';

        return view('students.dashboard', compact(
            'user', 
            'stats', 
            'admission',
            'studentClass',
            'studentName',
            'rollNumber',
            'admissionDate',
            'totalFee',
            'paidAmount',
            'pendingAmount',
            'nextDueDate',
            'isOverdue',
            'overdueDays',
            'isDueSoon',
            'paymentStatus'
        ));
    }

    /**
     * Display the student's timetable.
     */
    public function timetable()
    {
        $user = Auth::user();
        
        // Get student's admission record to find class
        $admission = Admission::where('email', $user->email)->first();
        $fullClass = $admission ? $admission->class : null;
        $studentClass = $fullClass;
        if ($fullClass && preg_match('/(\d+)/', $fullClass, $matches)) {
            $studentClass = $matches[1];
        }
        
        if (!$studentClass) {
            return view('students.timetable', [
                'studentClass' => null,
                'timetable' => collect(),
                'periodTimings' => collect(),
                'subjects' => collect()
            ]);
        }
        
        // Get published timetable for this class
        $timetable = Timetable::byClass($studentClass)
            ->with(['subject'])
            ->where('day', '!=', 'period_timing')
            ->published()
            ->get()
            ->groupBy(['day', 'period_number']);
        
        // Get published period timings
        $periodTimings = Timetable::where('class_name', $studentClass)
            ->where('day', 'period_timing')
            ->published()
            ->orderBy('period_number')
            ->get()
            ->keyBy('period_number');
        
        // Get subjects for this class
        $subjects = Subject::where('class_name', $studentClass)
            ->where('is_active', true)
            ->get();
            
        return view('students.timetable', compact(
            'studentClass',
            'fullClass',
            'timetable',
            'periodTimings',
            'subjects'
        ));
    }

    /**
     * Get timetable data for a class (API endpoint).
     */
    public function getTimetable($className)
    {
        // Normalize class name if needed (e.g., "6th" -> "6")
        if (preg_match('/(\d+)/', $className, $matches)) {
            $className = $matches[1];
        }
        
        // Get published timetable for this class
        $timetable = Timetable::byClass($className)
            ->with(['subject'])
            ->where('day', '!=', 'period_timing')
            ->published()
            ->get()
            ->groupBy(['day', 'period_number']);
        
        // Get published period timings
        $periodTimings = Timetable::where('class_name', $className)
            ->where('day', 'period_timing')
            ->published()
            ->orderBy('period_number')
            ->get()
            ->keyBy('period_number');
        
        return response()->json([
            'timetable' => $timetable,
            'period_timings' => $periodTimings,
        ]);
    }

    /**
     * Get subjects by class (API endpoint for AJAX requests).
     */
    public function getSubjectsByClass($className)
    {
        $subjects = Subject::where('class_name', $className)
            ->where('is_active', true)
            ->get(['id', 'name', 'code', 'teacher_name']);
        
        return response()->json($subjects);
    }
}
