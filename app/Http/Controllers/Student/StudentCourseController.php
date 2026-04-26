<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Admission;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    /**
     * Display the student's courses based on their class.
     */
    public function index(Request $request)
    {
        // Get the current authenticated student
        $student = Auth::user();
        
        // Get student's admission record to find their class and course type
        $admission = Admission::where('email', $student->email)->first();
        $studentClass = null;
        $classNumber = null;
        $courseType = null;
        
        if ($admission) {
            $studentClass = $admission->class;
            $courseType = $admission->course_type ?? 'REGULAR';
            
            // Extract class number for subject queries (handle different formats like "9th", "Class 9", etc.)
            if (preg_match('/(\d+)/', $studentClass, $matches)) {
                $classNumber = $matches[1];
            }
        }
        
        // Get subjects for the student's class and course type
        $subjects = [];
        if ($classNumber) {
            // For Class 5-10, always use REGULAR course type
            if ($classNumber >= 5 && $classNumber <= 10) {
                $courseType = 'REGULAR';
            }
            
            // Build query
            $query = Subject::where('class_name', $classNumber)
                ->where('is_active', true);
            
            // For classes 11 and 12, filter by course type
            if ($classNumber >= 11 && $courseType) {
                $query->where('course_type', $courseType);
            } else if ($classNumber <= 10) {
                // For classes 5-10, use REGULAR or subjects without course_type
                $query->where(function($q) {
                    $q->where('course_type', 'REGULAR')
                      ->orWhereNull('course_type');
                });
            }
            
            $subjects = $query->get();
        }
            
        return view('students.courses', compact('subjects', 'studentClass', 'classNumber', 'courseType'));
    }
}
