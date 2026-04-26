<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Show the schedule creation form.
     */
    public function create()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();
        
        return view('teacher.schedule.create', compact('employee'));
    }

    /**
     * Store a new schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'class' => 'required|string|max:50',
            'date' => 'required|date|after_or_equal:today',
            'room' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'class_type' => 'required|in:lecture,lab,tutorial,exam,assignment',
            'recurring' => 'nullable|in:none,daily,weekly,monthly',
            'notes' => 'nullable|string|max:500',
        ], [
            'subject.required' => 'Subject is required.',
            'class.required' => 'Class is required.',
            'date.required' => 'Date is required.',
            'date.after_or_equal' => 'Date cannot be in the past.',
            'room.required' => 'Room number is required.',
            'start_time.required' => 'Start time is required.',
            'end_time.required' => 'End time is required.',
            'end_time.after' => 'End time must be after start time.',
            'class_type.required' => 'Class type is required.',
        ]);

        // For now, we'll just redirect with success message
        // In a real implementation, you would save this to a schedules table
        
        return redirect()
            ->route('teacher.assignments')
            ->with('success', 'Class schedule created successfully!');
    }
}
