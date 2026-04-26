<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PTMSchedule;
use App\Models\Admission;
use App\Models\DoubtSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications (PTM and Doubt Sessions) for the current student
     */
    public function getPTMNotifications()
    {
        $student = Auth::user();
        $allNotifications = collect();
        
        // Get student's admission record
        $admission = Admission::where('email', $student->email)->first();
        
        if ($admission) {
            $studentClass = $admission->class;
            
            // Try to get course_type from remarks JSON if it exists
            $studentCourseType = null;
            if ($admission->remarks) {
                $remarks = is_string($admission->remarks) ? json_decode($admission->remarks, true) : $admission->remarks;
                if (isset($remarks['original_data']['course'])) {
                    // If course is an array, get the first course type
                    $courses = is_array($remarks['original_data']['course']) ? $remarks['original_data']['course'] : [];
                    $studentCourseType = !empty($courses) ? $courses[0] : 'REGULAR';
                }
            }
            
            // Normalize class name - remove suffixes like "th", "st", "nd", "rd"
            if ($studentClass) {
                $studentClass = preg_replace('/(\d+)(?:st|nd|rd|th)/i', '$1', $studentClass);
            }
            
            // --- Fetch PTM Notifications ---
            $ptmQuery = PTMSchedule::where('class_name', $studentClass);
            
            // For classes 11 and 12, filter by course type
            if (in_array($studentClass, ['11', '12']) && $studentCourseType) {
                $ptmQuery->where(function($q) use ($studentCourseType) {
                    $q->where('course_type', $studentCourseType)
                      ->orWhereNull('course_type');
                });
            }
            
            // For repeater programs, only show their specific course type
            if ($studentCourseType === 'NEET Repeater') {
                $ptmQuery->where('course_type', 'NEET Repeater');
            } elseif ($studentCourseType === 'JEE Repeater') {
                $ptmQuery->where('course_type', 'JEE Repeater');
            }
            
            $ptmNotifications = $ptmQuery->where(function($q) {
                    $q->where('meeting_date', '>=', now()->format('Y-m-d')) // Upcoming meetings
                      ->orWhere('created_at', '>=', now()->subDays(3)); // Recently created (3 days)
                })
                ->orderBy('meeting_date', 'asc')
                ->orderBy('created_at', 'desc')
                ->limit(10) // Limit to latest 10
                ->get()
                ->map(function($notification) {
                    return [
                        'id' => 'ptm-'.$notification->id,
                        'type' => 'ptm',
                        'teacher_name' => $notification->teacher_name,
                        'meeting_date' => $notification->meeting_date,
                        'start_time' => $notification->start_time,
                        'meeting_mode' => $notification->meeting_mode,
                        'class_name' => $notification->class_name,
                        'course_type' => $notification->course_type,
                        'description' => $notification->description,
                        'created_at' => $notification->created_at,
                    ];
                });
            
            // --- Fetch Doubt Session Notifications ---
            $numericClass = preg_replace('/[^0-9]/', '', $admission->class);
            
            $doubtSessions = DoubtSession::where('status', 'published')
                ->where('class_name', $numericClass)
                ->where(function($q) {
                    $q->where('session_date', '>=', now()->format('Y-m-d')) // Upcoming sessions
                      ->orWhere('created_at', '>=', now()->subDays(3)); // Recently created (3 days)
                })
                ->with(['subject', 'teacher'])
                ->orderBy('session_date', 'asc')
                ->orderBy('created_at', 'desc')
                ->limit(10) // Limit to latest 10
                ->get()
                ->map(function($session) {
                    return [
                        'id' => 'doubt-'.$session->id,
                        'type' => 'doubt',
                        'teacher_name' => $session->teacher->name ?? 'Teacher',
                        'meeting_date' => $session->session_date,
                        'start_time' => $session->start_time,
                        'meeting_mode' => 'online', // Doubt sessions are typically online
                        'class_name' => $session->class_name,
                        'course_type' => $session->subject->name ?? '',
                        'description' => $session->description,
                        'subject_name' => $session->subject->name ?? '',
                        'created_at' => $session->created_at,
                    ];
                });
            
            // Combine both types of notifications
            $allNotifications = $ptmNotifications->merge($doubtSessions)
                ->sortByDesc('created_at')
                ->values();
            
            // Get read notifications from session
            $readNotifications = session('read_notifications', []);
            
            // Filter out read notifications
            $notifications = $allNotifications->filter(function($notification) use ($readNotifications) {
                return !in_array($notification['id'], $readNotifications);
            });
        }
        
        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }
    
    /**
     * Mark PTM notifications as read
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        
        if ($notificationId) {
            // Mark specific notification as read
            $readNotifications = session('read_notifications', []);
            if (!in_array($notificationId, $readNotifications)) {
                $readNotifications[] = $notificationId;
                session(['read_notifications' => $readNotifications]);
            }
        }
        
        // Store the timestamp when notifications were marked as read
        session(['ptm_notifications_read_at' => now()->toISOString()]);
        
        return response()->json(['success' => true, 'notification_id' => $notificationId]);
    }
    
    /**
     * Check if there are new PTM notifications since last read
     */
    public function checkNewNotifications()
    {
        $student = Auth::user();
        $admission = Admission::where('email', $student->email)->first();
        
        if (!$admission) {
            return response()->json(['has_new' => false]);
        }
        
        $lastReadAt = session('ptm_notifications_read_at');
        
        // Normalize class name - remove suffixes like "th", "st", "nd", "rd"
        $studentClass = $admission->class;
        if ($studentClass) {
            $studentClass = preg_replace('/(\d+)(?:st|nd|rd|th)/i', '$1', $studentClass);
        }
        
        // Get read notifications from session
        $readNotifications = session('read_notifications', []);
        
        // Get all notifications (PTM + Doubt Sessions)
        $allNotifications = $this->getAllNotificationsForStudent($admission);
        
        // Filter out read notifications
        $unreadNotifications = $allNotifications->filter(function($notification) use ($readNotifications) {
            return !in_array($notification['id'], $readNotifications);
        });
        
        return response()->json(['has_new' => $unreadNotifications->count() > 0]);
    }
    
    /**
     * Helper method to get all notifications for a student
     */
    private function getAllNotificationsForStudent($admission)
    {
        $studentClass = $admission->class;
        
        // Try to get course_type from remarks JSON if it exists
        $studentCourseType = null;
        if ($admission->remarks) {
            $remarks = is_string($admission->remarks) ? json_decode($admission->remarks, true) : $admission->remarks;
            if (isset($remarks['original_data']['course'])) {
                // If course is an array, get the first course type
                $courses = is_array($remarks['original_data']['course']) ? $remarks['original_data']['course'] : [];
                $studentCourseType = !empty($courses) ? $courses[0] : 'REGULAR';
            }
        }
        
        // Normalize class name - remove suffixes like "th", "st", "nd", "rd"
        if ($studentClass) {
            $studentClass = preg_replace('/(\d+)(?:st|nd|rd|th)/i', '$1', $studentClass);
        }
        
        $allNotifications = collect();
        
        // --- Fetch PTM Notifications ---
        $ptmQuery = PTMSchedule::where('class_name', $studentClass);
        
        // For classes 11 and 12, filter by course type
        if (in_array($studentClass, ['11', '12']) && $studentCourseType) {
            $ptmQuery->where(function($q) use ($studentCourseType) {
                $q->where('course_type', $studentCourseType)
                  ->orWhereNull('course_type');
            });
        }
        
        // For repeater programs, only show their specific course type
        if ($studentCourseType === 'NEET Repeater') {
            $ptmQuery->where('course_type', 'NEET Repeater');
        } elseif ($studentCourseType === 'JEE Repeater') {
            $ptmQuery->where('course_type', 'JEE Repeater');
        }
        
        $ptmNotifications = $ptmQuery->where(function($q) {
                $q->where('meeting_date', '>=', now()->format('Y-m-d')) // Upcoming meetings
                  ->orWhere('created_at', '>=', now()->subDays(3)); // Recently created (3 days)
            })
            ->orderBy('meeting_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(10) // Limit to latest 10
            ->get()
            ->map(function($notification) {
                return [
                    'id' => 'ptm-'.$notification->id,
                    'type' => 'ptm',
                    'teacher_name' => $notification->teacher_name,
                    'meeting_date' => $notification->meeting_date,
                    'start_time' => $notification->start_time,
                    'meeting_mode' => $notification->meeting_mode,
                    'class_name' => $notification->class_name,
                    'course_type' => $notification->course_type,
                    'description' => $notification->description,
                    'created_at' => $notification->created_at,
                ];
            });
        
        // --- Fetch Doubt Session Notifications ---
        $numericClass = preg_replace('/[^0-9]/', '', $admission->class);
        
        $doubtSessions = DoubtSession::where('status', 'published')
            ->where('class_name', $numericClass)
            ->where(function($q) {
                $q->where('session_date', '>=', now()->format('Y-m-d')) // Upcoming sessions
                  ->orWhere('created_at', '>=', now()->subDays(3)); // Recently created (3 days)
            })
            ->with(['subject', 'teacher'])
            ->orderBy('session_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(10) // Limit to latest 10
            ->get()
            ->map(function($session) {
                return [
                    'id' => 'doubt-'.$session->id,
                    'type' => 'doubt',
                    'teacher_name' => $session->teacher->name ?? 'Teacher',
                    'meeting_date' => $session->session_date,
                    'start_time' => $session->start_time,
                    'meeting_mode' => 'online', // Doubt sessions are typically online
                    'class_name' => $session->class_name,
                    'course_type' => $session->subject->name ?? '',
                    'description' => $session->description,
                    'subject_name' => $session->subject->name ?? '',
                    'created_at' => $session->created_at,
                ];
            });
        
        // Combine both types of notifications
        return $ptmNotifications->merge($doubtSessions)
            ->sortByDesc('created_at')
            ->values();
    }
}
