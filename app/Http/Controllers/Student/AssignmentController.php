<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display student assignments list.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student's admission record
        $admission = Admission::where('email', $user->email)->first();
        $studentClass = $admission ? $admission->class : null;
        
        // Extract class number
        $classNumber = $studentClass;
        if ($studentClass && preg_match('/(\d+)/', $studentClass, $matches)) {
            $classNumber = $matches[1];
        }

        if (!$classNumber) {
            $assignments = collect();
        } else {
            $assignments = Assignment::where('class_id', $classNumber)
                ->orWhere('class_id', $studentClass)
                ->with(['teacher', 'submissions' => function($query) use ($user) {
                    $query->where('student_id', $user->id);
                }])
                ->orderBy('due_date', 'asc')
                ->get();
        }

        // Map statuses dynamically
        $assignments->transform(function ($assignment) {
            $submission = $assignment->submissions->first();
            
            if ($submission) {
                $assignment->status = ucfirst($submission->status);
                $assignment->submission_date = $submission->submitted_at;
                $assignment->marks = $submission->marks;
                
                $submittedAt = \Carbon\Carbon::parse($submission->submitted_at);
                $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                $assignment->is_late = $submittedAt->gt($dueDate);
            } elseif (now()->gt(\Carbon\Carbon::parse($assignment->due_date))) {
                $assignment->status = 'Overdue';
                $assignment->is_late = false;
                $assignment->marks = null;
            } else {
                $assignment->status = 'Pending';
                $assignment->is_late = false;
                $assignment->marks = null;
            }
            
            return $assignment;
        });

        return view('students.assignments', compact('assignments', 'studentClass'));
    }

    /**
     * Handle submission uploads.
     */
    public function submit(Request $request, $id)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:pdf|max:10240', 
        ]);

        $assignment = Assignment::findOrFail($id);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_submission_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('submissions', $fileName, 'public');
        }

        $submission = AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => auth()->id(),
            ],
            [
                'file_path' => $filePath,
                'submitted_at' => now(),
                'status' => 'submitted',
            ]
        );

        // Send Notification to Teacher
        $studentName = auth()->user()->name;
        $submissionTime = now()->format('M d, Y h:i A');
        
        \App\Models\Notification::create([
            'user_id' => $assignment->teacher_id,
            'sender_id' => auth()->id(),
            'title' => 'New Submission Received',
            'message' => "{$studentName} submitted assignment '{$assignment->title}' at {$submissionTime}.",
            'type' => 'assignment_submission',
            'data' => [
                'redirect_url' => route('teacher.assignments.assignment') . '?open=' . $assignment->id,
                'submission_id' => $submission->id
            ]
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Assignment submitted successfully!',
                'submission' => $submission
            ]);
        }

        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }
}
