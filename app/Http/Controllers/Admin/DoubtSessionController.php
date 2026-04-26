<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoubtSession;
use App\Models\Subject;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoubtSessionController extends Controller
{
    /**
     * Display a listing of doubt sessions.
     */
    public function index()
    {
        // Redirect to subjects classes page since we're using popup approach
        return redirect()->route('admin.subjects.classes')
            ->with('info', 'Use the "Doubt Session" button on any class page to create doubt sessions.');
    }

    /**
     * Show the form for creating a new doubt session.
     */
    public function create()
    {
        // Redirect to subjects classes page since we're using popup approach
        return redirect()->route('admin.subjects.classes')
            ->with('info', 'Use the "Doubt Session" button on any class page to create doubt sessions.');
    }

    /**
     * Store a newly created doubt session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'class_name' => 'required|string',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published'
        ]);

        $session = DoubtSession::create($request->only([
            'subject_id',
            'teacher_id',
            'class_name',
            'session_date',
            'start_time',
            'end_time',
            'description',
            'status'
        ]));

        // Load the subject relationship to get class name
        $session->load('subject');
        $className = $session->subject->class_name;
        
        if ($session->status === 'published') {
            // Notify students in this class about the doubt session
            $formattedDate = date('d M Y', strtotime($session->session_date));
            $formattedTime = date('h:i A', strtotime($session->start_time));
            NotificationService::notifyStudentsByClass(
                $session->class_name,
                'Doubt Session Scheduled',
                "A doubt session for {$session->subject->name} is scheduled on {$formattedDate} at {$formattedTime}.",
                'doubt',
                [
                    'doubt_session_id' => $session->id,
                    'session_date' => $session->session_date,
                ]
            );

            // Also notify admins so it shows in their notification dropdown
            NotificationService::notifyAdmins(
                'Doubt Session Published',
                "Class {$session->class_name} - {$session->subject->name} scheduled on {$formattedDate}.",
                'doubt',
                [
                    'doubt_session_id' => $session->id,
                    'redirect_url' => '/admin/subjects/class/' . $session->class_name
                ]
            );

            $doubtSessionNotification = [
                'title' => 'Doubt Session Scheduled',
                'message' => "Class: {$session->class_name} - {$session->subject->name} - " . date('d M Y h:i A', strtotime($session->session_date . ' ' . $session->start_time)),
                'datetime' => date('d M Y', strtotime($session->session_date)) . ' - ' . date('h:i A', strtotime($session->start_time)),
                'id' => $session->id,
            ];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Doubt session created and published successfully!',
                    'notification' => $doubtSessionNotification
                ]);
            }

            return redirect()
                ->route('admin.subjects.class', $className)
                ->with('success', 'Doubt session created and published successfully!');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Doubt session created as draft successfully!'
            ]);
        }

        return redirect()
            ->route('admin.subjects.class', $className)
            ->with('success', 'Doubt session created as draft successfully!');
    }

    /**
     * Display the specified doubt session.
     */
    public function show(DoubtSession $doubtSession)
    {
        // Redirect to subjects classes page since we're using popup approach
        return redirect()->route('admin.subjects.classes')
            ->with('info', 'Use the "Doubt Session" button on any class page to manage doubt sessions.');
    }

    /**
     * Show the form for editing the specified doubt session.
     */
    public function edit(DoubtSession $doubtSession)
    {
        // Redirect to subjects classes page since we're using popup approach
        return redirect()->route('admin.subjects.classes')
            ->with('info', 'Use the "Doubt Session" button on any class page to manage doubt sessions.');
    }

    /**
     * Update the specified doubt session.
     */
    public function update(Request $request, DoubtSession $doubtSession)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'class_name' => 'required|string',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published'
        ]);

        $originalStatus = $doubtSession->status;
        
        $doubtSession->update($request->only([
            'subject_id',
            'teacher_id',
            'class_name',
            'session_date',
            'start_time',
            'end_time',
            'description',
            'status'
        ]));

        // Get class name from the session itself now
        $className = $doubtSession->class_name;

        // If status changed from draft to published, send notification
        if ($originalStatus !== 'published' && $doubtSession->status === 'published') {
            $doubtSession->load('subject');
            $formattedDate = date('d M Y', strtotime($doubtSession->session_date));
            $formattedTime = date('h:i A', strtotime($doubtSession->start_time));
            
            \App\Services\NotificationService::notifyStudentsByClass(
                $doubtSession->class_name,
                'Doubt Session Scheduled',
                "A doubt session for {$doubtSession->subject->name} is scheduled on {$formattedDate} at {$formattedTime}.",
                'doubt',
                [
                    'doubt_session_id' => $doubtSession->id,
                    'session_date' => $doubtSession->session_date,
                ]
            );

            // Also notify admins
            \App\Services\NotificationService::notifyAdmins(
                'Doubt Session Published',
                "Class {$doubtSession->class_name} - {$doubtSession->subject->name} scheduled on {$formattedDate}.",
                'doubt',
                [
                    'doubt_session_id' => $doubtSession->id,
                    'redirect_url' => '/admin/subjects/class/' . $doubtSession->class_name
                ]
            );
        }

        if ($doubtSession->status === 'published') {
            return redirect()
                ->route('admin.subjects.class', $className)
                ->with('success', 'Doubt session updated and published successfully!');
        }

        return redirect()
            ->route('admin.subjects.class', $className)
            ->with('success', 'Doubt session updated successfully!');
    }

    /**
     * Remove the specified doubt session.
     */
    public function destroy(DoubtSession $doubtSession)
    {
        // Load the subject relationship to get class name before deleting
        $doubtSession->load('subject');
        $className = $doubtSession->subject->class_name;
        
        $doubtSession->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Doubt session deleted successfully!']);
        }

        return redirect()
            ->route('admin.subjects.class', $className)
            ->with('success', 'Doubt session deleted successfully!');
    }

    /**
     * Get subjects for a specific class (AJAX endpoint).
     */
    public function getSubjectsByClass($className)
    {
        $subjects = Subject::where('class_name', $className)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subjects);
    }

    /**
     * Toggle session status (draft/published).
     */
    public function toggleStatus(DoubtSession $doubtSession)
    {
        $newStatus = $doubtSession->status === 'published' ? 'draft' : 'published';
        $doubtSession->update(['status' => $newStatus]);

        // Notify students when a session is published
        if ($newStatus === 'published') {
            $doubtSession->load('subject');
            $formattedDate = date('d M Y', strtotime($doubtSession->session_date));
            $formattedTime = date('h:i A', strtotime($doubtSession->start_time));
            NotificationService::notifyStudentsByClass(
                $doubtSession->class_name,
                'Doubt Session Scheduled',
                "A doubt session for {$doubtSession->subject->name} is scheduled on {$formattedDate} at {$formattedTime}.",
                'doubt',
                [
                    'doubt_session_id' => $doubtSession->id,
                    'session_date' => $doubtSession->session_date,
                ]
            );

            // Also notify admins
            NotificationService::notifyAdmins(
                'Doubt Session Published',
                "Class {$doubtSession->class_name} - {$doubtSession->subject->name} scheduled on {$formattedDate}.",
                'doubt',
                [
                    'doubt_session_id' => $doubtSession->id,
                    'redirect_url' => '/admin/subjects/class/' . $doubtSession->class_name
                ]
            );
        }

        $message = $newStatus === 'published' 
            ? 'Doubt session published successfully!' 
            : 'Doubt session set to draft!';

        return redirect()
            ->route('admin.doubt-sessions.index')
            ->with('success', $message);
    }
}
