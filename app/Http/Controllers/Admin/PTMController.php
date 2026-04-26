<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PTMSchedule;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PTMController extends Controller
{
    public function index()
    {
        $ptmSchedules = PTMSchedule::orderBy('meeting_date', 'desc')
                                  ->orderBy('start_time', 'desc')
                                  ->get();
        
        return view('admin.ptm.index', compact('ptmSchedules'));
    }

    public function store(Request $request)
    {
        $rules = [
            'class_name' => 'required|string',
            'course_type' => 'nullable|string|required_if:class_name,11,12',
            'meeting_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'teacher_name' => 'required|string|regex:/^[a-zA-Z\s\.]+$/',
            'meeting_mode' => 'required|in:online,offline',
            'description' => 'nullable|string',
        ];

        $messages = [
            'meeting_date.after_or_equal' => 'Meeting date cannot be in the past.',
            'end_time.after' => 'End time must be after the start time.',
            'teacher_name.regex' => 'Teacher name should only contain letters and spaces.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Handle meeting mode specific validation
        if ($request->meeting_mode === 'online') {
            $request->validate([
                'meeting_link' => 'required|url'
            ], [
                'meeting_link.required' => 'Meeting link is required for online meetings.',
                'meeting_link.url' => 'Please enter a valid URL for the meeting link.',
            ]);
            $validatedData['meeting_link'] = $request->meeting_link;
            $validatedData['meeting_location'] = null;
        } elseif ($request->meeting_mode === 'offline') {
            $request->validate([
                'meeting_location' => 'required|string'
            ], [
                'meeting_location.required' => 'Meeting location is required for offline meetings.',
            ]);
            $validatedData['meeting_location'] = $request->meeting_location;
            $validatedData['meeting_link'] = null;
        }

        $ptmSchedule = PTMSchedule::create([
            'class_name' => $validatedData['class_name'],
            'course_type' => $validatedData['course_type'],
            'meeting_date' => $validatedData['meeting_date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'teacher_name' => $validatedData['teacher_name'],
            'meeting_mode' => $validatedData['meeting_mode'],
            'meeting_link' => $validatedData['meeting_link'] ?? null,
            'meeting_location' => $validatedData['meeting_location'] ?? null,
            'description' => $validatedData['description'],
            'status' => 'scheduled',
        ]);

        // Trigger PTM notification for students
        Session::flash('ptm_notification', [
            'title' => 'New PTM Scheduled',
            'message' => "Class: {$ptmSchedule->class_name}" . ($ptmSchedule->course_type ? " ({$ptmSchedule->course_type})" : "") . " - {$ptmSchedule->teacher_name}",
            'datetime' => date('d M Y', strtotime($ptmSchedule->meeting_date)) . ' - ' . date('h:i A', strtotime($ptmSchedule->start_time)),
            'id' => $ptmSchedule->id,
        ]);

        // Notify all students in this class about the PTM
        $formattedDate = date('d M Y', strtotime($ptmSchedule->meeting_date));
        $formattedTime = date('h:i A', strtotime($ptmSchedule->start_time));
        NotificationService::notifyStudentsByClass(
            $ptmSchedule->class_name,
            'PTM Meeting Scheduled',
            "A Parent-Teacher Meeting is scheduled on {$formattedDate} at {$formattedTime} with {$ptmSchedule->teacher_name}.",
            'ptm',
            [
                'ptm_id' => $ptmSchedule->id,
                'meeting_date' => $ptmSchedule->meeting_date,
                'start_time' => $ptmSchedule->start_time,
            ]
        );

        // Also notify admins
        NotificationService::notifyAdmins(
            'PTM Published',
            "Class {$ptmSchedule->class_name} - PTM scheduled on {$formattedDate}.",
            'ptm',
            [
                'ptm_id' => $ptmSchedule->id,
                'redirect_url' => '/admin/subjects/classes'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'PTM scheduled successfully!',
            'notification' => [
                'title' => 'New PTM Scheduled',
                'message' => "Class: {$ptmSchedule->class_name}" . ($ptmSchedule->course_type ? " ({$ptmSchedule->course_type})" : "") . " - {$ptmSchedule->teacher_name}",
                'datetime' => date('d M Y', strtotime($ptmSchedule->meeting_date)) . ' - ' . date('h:i A', strtotime($ptmSchedule->start_time)),
                'id' => $ptmSchedule->id,
            ]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $ptmSchedule = PTMSchedule::findOrFail($id);
        $ptmSchedule->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'PTM status updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $ptmSchedule = PTMSchedule::findOrFail($id);
        $ptmSchedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'PTM deleted successfully!'
        ]);
    }
}
