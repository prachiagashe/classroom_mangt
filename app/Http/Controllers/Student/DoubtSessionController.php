<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DoubtSession;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoubtSessionController extends Controller
{
    /**
     * Display doubt sessions for the student's class.
     */
    public function index()
    {
        $student = Auth::user();
        
        // Get student's admission record
        $admission = Admission::where('email', $student->email)->first();
        
        if (!$admission) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Admission record not found');
        }

        // Normalize class name - extract numeric class only (6th -> 6, 7th -> 7, etc.)
        $studentClass = $admission->class;
        $numericClass = preg_replace('/[^0-9]/', '', $studentClass);
        
        // Get current date and time
        $now = Carbon::now();
        $currentDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');
        
        // Get all published doubt sessions for student's class
        $allSessions = DoubtSession::where('status', 'published')
            ->where('class_name', $numericClass)
            ->with(['subject', 'teacher'])
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        // Separate sessions into upcoming and past based on actual time comparison
        $upcomingSessions = collect();
        $pastSessions = collect();

        foreach ($allSessions as $session) {
            // Debug: Log raw values
            \Log::info('Raw Session Data - ID: ' . $session->id . 
                      ' | Date: ' . $session->session_date . 
                      ' | End Time: ' . $session->end_time . 
                      ' | Current Date: ' . $currentDate . 
                      ' | Current Time: ' . $currentTime);
            
            try {
                // Method 1: Direct date and time comparison
                $sessionDate = $session->session_date;
                $sessionEndTime = $session->end_time;
                
                // If session date is in the past, it's definitely past
                if ($sessionDate < $currentDate) {
                    $pastSessions->push($session);
                    \Log::info('Session ' . $session->id . ' is PAST (date is earlier)');
                    continue;
                }
                
                // If session date is in the future, it's definitely upcoming
                if ($sessionDate > $currentDate) {
                    $upcomingSessions->push($session);
                    \Log::info('Session ' . $session->id . ' is UPCOMING (date is later)');
                    continue;
                }
                
                // If session date is today, compare times
                if ($sessionDate === $currentDate) {
                    // Normalize times to comparable format
                    $sessionEndTimeNormalized = strlen($sessionEndTime) === 5 ? $sessionEndTime . ':00' : $sessionEndTime;
                    
                    if ($sessionEndTimeNormalized <= $currentTime) {
                        $pastSessions->push($session);
                        \Log::info('Session ' . $session->id . ' is PAST (today but time passed)');
                    } else {
                        $upcomingSessions->push($session);
                        \Log::info('Session ' . $session->id . ' is UPCOMING (today and time not passed)');
                    }
                }
                
            } catch (\Exception $e) {
                \Log::error('Error in session comparison: ' . $e->getMessage());
                // Default to upcoming if there's an error
                $upcomingSessions->push($session);
            }
        }

        return view('students.doutesession', compact(
            'upcomingSessions',
            'pastSessions',
            'studentClass'
        ));
    }

    /**
     * Get doubt session details.
     */
    public function show(DoubtSession $doubtSession)
    {
        // Verify this session is published and for student's class
        $student = Auth::user();
        $admission = Admission::where('email', $student->email)->first();
        
        if (!$admission || $doubtSession->status !== 'published') {
            abort(404);
        }

        // Check if session belongs to student's class
        if ($doubtSession->subject->class_name !== $admission->class) {
            abort(403, 'This session is not for your class');
        }

        $doubtSession->load(['subject', 'teacher']);

        return view('students.doubt-session-details', compact('doubtSession'));
    }

    /**
     * Get upcoming doubt sessions for dashboard (API).
     */
    public function getUpcomingSessions()
    {
        $student = Auth::user();
        $admission = Admission::where('email', $student->email)->first();
        
        if (!$admission) {
            return response()->json(['sessions' => []]);
        }

        // Normalize class name - extract numeric class only (6th -> 6, 7th -> 7, etc.)
        $numericClass = preg_replace('/[^0-9]/', '', $admission->class);

        // Get current date and time
        $now = Carbon::now();

        // Get all published doubt sessions for student's class
        $allSessions = DoubtSession::where('status', 'published')
            ->where('class_name', $numericClass)
            ->with(['subject', 'teacher'])
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        // Filter only upcoming sessions
        $upcomingSessions = $allSessions->filter(function($session) use ($now) {
            try {
                // Create proper datetime for session end time
                $sessionEndDateTime = Carbon::parse($session->session_date . ' ' . $session->end_time);
                return $sessionEndDateTime->gt($now);
            } catch (\Exception $e) {
                // If parsing fails, assume it's upcoming to avoid losing sessions
                return true;
            }
        })->take(3);

        return response()->json(['sessions' => $upcomingSessions]);
    }
}
