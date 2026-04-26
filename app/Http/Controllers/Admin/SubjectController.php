<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\Employee\Employee;

class SubjectController extends Controller
{
    /**
     * Display a listing of all classes with subject counts.
     */
    public function classes()
    {
        // Get classes from 5th to 12th with subject counts
        $classes = [];
        for ($i = 5; $i <= 12; $i++) {
            $subjectCount = Subject::where('class_name', $i)->count();
            
            $classes[] = [
                'name' => $i,
                'subject_count' => $subjectCount
            ];
        }
        
        // Add repeater programs
        $neetCount = Subject::where('course_type', 'NEET Repeater')->count();
        $jeeCount = Subject::where('course_type', 'JEE Repeater')->count();
        
        $classes[] = [
            'name' => 'NEET Repeater',
            'subject_count' => $neetCount ?: 8, // Default count if no subjects yet
            'is_repeater' => true
        ];
        
        $classes[] = [
            'name' => 'JEE Repeater',
            'subject_count' => $jeeCount ?: 10, // Default count if no subjects yet
            'is_repeater' => true
        ];
        
        return view('admin.subjects.classes', compact('classes'));
    }
    
    /**
     * Display subjects for a specific class.
     */
    public function class($className)
    {
        // Handle repeater programs
        if ($className === 'NEET Repeater') {
            $subjects = Subject::where('course_type', 'NEET Repeater')
                ->where('is_active', true)
                ->get();
        } elseif ($className === 'JEE Repeater') {
            $subjects = Subject::where('course_type', 'JEE Repeater')
                ->where('is_active', true)
                ->get();
        } else {
            // Regular classes
            $subjects = Subject::where('class_name', $className)
                ->where('is_active', true)
                ->get();
        }
            
        $studentRequests = collect(); // You can add logic for student requests if needed
        
        return view('admin.subjects.class-subjects', compact('className', 'subjects', 'studentRequests'));
    }
    
    /**
     * Store a new subject.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'class_name' => 'required|string',
            'course_type' => 'required|string|in:REGULAR,NEET,JEE,MHT-CET,NEET Repeater,JEE Repeater',
            'teacher_name' => 'required|string',
            'teacher_email' => 'nullable|email',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $subjectData = $request->all();
        
        // Set default values
        if (!isset($subjectData['is_active'])) {
            $subjectData['is_active'] = true;
        }
        if (!isset($subjectData['credits'])) {
            $subjectData['credits'] = 4; // Default credits
        }

        $subject = Subject::create($subjectData);
        
        return redirect()->route('admin.subjects.class', $request->class_name)
            ->with('success', 'Subject added successfully!');
    }
    
    /**
     * Show the form for editing a subject.
     */
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }
    
    /**
     * Update a subject.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'course_type' => 'nullable|string|in:REGULAR,NEET,JEE,MHT-CET,NEET Repeater,JEE Repeater',
            'teacher_name' => 'required|string',
            'teacher_email' => 'nullable|email',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $subjectData = $request->all();
        
        // Set default value for is_active if not provided
        if (!isset($subjectData['is_active'])) {
            $subjectData['is_active'] = true;
        }

        $subject->update($subjectData);

        return redirect()->route('admin.subjects.class', $subject->class_name)
            ->with('success', 'Subject updated successfully!');
    }
    
    /**
     * Delete a subject.
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $className = $subject->class_name;
        $subject->delete();
        
        return redirect()->route('admin.subjects.class', $className)
            ->with('success', 'Subject deleted successfully!');
    }

    /**
     * Save timetable for a class.
     */
    public function saveTimetable(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'period_timings' => 'required|array',
            'schedule' => 'required|array',
        ]);

        $className = $request->class_name;
        
        // Delete existing timetable for this class
        Timetable::where('class_name', $className)->delete();
        
        // Save period timings
        $periodTimings = $request->period_timings;
        foreach ($periodTimings as $period => $timing) {
            if (isset($timing['start']) && isset($timing['end'])) {
                $periodNumber = (int) str_replace('period', '', $period);
                Timetable::create([
                    'class_name' => $className,
                    'day' => 'period_timing', // Special marker for period timings
                    'period_number' => $periodNumber,
                    'start_time' => $timing['start'],
                    'end_time' => $timing['end'],
                ]);
            }
        }
        
        // Save schedule
        $schedule = $request->schedule;
        foreach ($schedule as $day => $periods) {
            foreach ($periods as $period => $subjectId) {
                if ($subjectId) {
                    $periodNumber = (int) str_replace('period', '', $period);
                    Timetable::create([
                        'class_name' => $className,
                        'day' => $day,
                        'period_number' => $periodNumber,
                        'subject_id' => $subjectId,
                    ]);
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Timetable saved successfully!'
        ]);
    }

    /**
     * Publish subjects for a class.
     */
    public function publishSubjects(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
        ]);

        $className = $request->class_name;
        
        // Find all subjects for this class
        $subjects = Subject::where('class_name', $className)->get();
        
        if ($subjects->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No subjects found to publish.'
            ]);
        }

        // Update all subjects for this class to active (published)
        Subject::where('class_name', $className)->update(['is_active' => true]);

        // Send notification to students about the subjects
        \App\Services\NotificationService::notifyStudentsByClass(
            $className,
            'Subjects Published',
            "The subjects for Class {$className} have been published.",
            'subject'
        );

        return response()->json([
            'success' => true,
            'message' => "Subjects for Class {$className} have been published successfully!"
        ]);
    }

    /**
     * Publish timetable for a class.
     */
    public function publishTimetable(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
        ]);

        $className = $request->class_name;
        
        // Update all timetable entries for this class to published
        Timetable::where('class_name', $className)->update(['published' => true]);
        
        // Notify students about the new timetable
        \App\Services\NotificationService::notifyStudentsByClass(
            $className,
            'Timetable Published',
            "The timetable for Class {$className} has been published.",
            'timetable'
        );
        
        return response()->json([
            'success' => true,
            'message' => "Timetable for Class {$className} has been published successfully!"
        ]);
    }

    /**
     * Get timetable data for a class.
     */
    public function getTimetable($className)
    {
        $timetable = Timetable::byClass($className)
            ->with(['subject'])
            ->get()
            ->groupBy(['day', 'period_number']);
        
        $periodTimings = Timetable::where('class_name', $className)
            ->where('day', 'period_timing')
            ->orderBy('period_number')
            ->get()
            ->keyBy('period_number');
        
        return response()->json([
            'timetable' => $timetable,
            'period_timings' => $periodTimings,
        ]);
    }
}
