<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PTMSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PTMController extends Controller
{
    public function index()
    {
        // Get student's class and course type from admissions
        $student = Auth::user();
        $studentClass = null;
        $studentCourseType = null;
        
        // Get student's admission record by email
        $admission = \App\Models\Admission::where('email', $student->email)->first();
        if ($admission) {
            $studentClass = $admission->class;
            
            // Try to get course_type from remarks JSON if it exists
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
        }
        
        if (!$studentClass) {
            return view('students.ptm-meetings', [
                'upcomingMeetings' => collect(),
                'pastMeetings' => collect(),
                'studentClass' => null,
                'studentCourseType' => null,
                'newNotifications' => collect()
            ]);
        }

        // Get PTM schedules for this student
        $baseQuery = PTMSchedule::where('class_name', $studentClass);
        
        // For classes 11 and 12, also filter by course type
        if (in_array($studentClass, ['11', '12']) && $studentCourseType) {
            $baseQuery->where(function($q) use ($studentCourseType) {
                $q->where('course_type', $studentCourseType)
                  ->orWhereNull('course_type'); // Also show PTMs without course type
            });
        }
        
        // Also check for repeater programs
        if ($studentCourseType === 'NEET Repeater') {
            $baseQuery->orWhere('course_type', 'NEET Repeater');
        } elseif ($studentCourseType === 'JEE Repeater') {
            $baseQuery->orWhere('course_type', 'JEE Repeater');
        }
        
        // Clone the base query for each operation to avoid filter conflicts
        $upcomingQuery = clone $baseQuery;
        $pastQuery = clone $baseQuery;
        $notificationsQuery = clone $baseQuery;
        
        // Get upcoming meetings (only those that haven't started yet)
        $upcomingMeetings = $upcomingQuery->where('status', 'scheduled')
                               ->where(function($q) {
                                   $currentTime = now()->format('H:i:s');
                                   $currentDate = now()->toDateString();
                                   $q->where('meeting_date', '>', $currentDate)
                                     ->orWhere(function($subQ) use ($currentDate, $currentTime) {
                                         $subQ->where('meeting_date', '=', $currentDate)
                                                ->where('start_time', '>', $currentTime);
                                     });
                               })
                               ->orderBy('meeting_date', 'asc')
                               ->orderBy('start_time', 'asc')
                               ->get();
        
        // Get past meetings (meetings that have already passed or are completed/cancelled)
        $pastMeetings = $pastQuery->where(function($q) {
                            $currentTime = now()->format('H:i:s');
                            $currentDate = now()->toDateString();
                            $q->where('meeting_date', '<', $currentDate)
                              ->orWhere(function($subQ) use ($currentDate, $currentTime) {
                                  $subQ->where('meeting_date', '=', $currentDate)
                                         ->where('start_time', '<=', $currentTime);
                              })
                              ->orWhere('status', 'completed')
                              ->orWhere('status', 'cancelled');
                        })
                        ->orderBy('meeting_date', 'desc')
                        ->orderBy('start_time', 'desc')
                        ->limit(4) // Limit to only 4 past meetings
                        ->get();

        // Get new PTM notifications (created in last 7 days)
        $newNotifications = $notificationsQuery->where('created_at', '>=', now()->subDays(7))
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('students.ptm-meetings', compact(
            'upcomingMeetings',
            'pastMeetings',
            'studentClass',
            'studentCourseType',
            'newNotifications'
        ));
    }

    public function joinMeeting($id)
    {
        $ptmSchedule = PTMSchedule::findOrFail($id);
        
        // Get student's class and course type
        $student = Auth::user();
        $admission = \App\Models\Admission::where('email', $student->email)->first();
        
        if (!$admission) {
            return response()->json(['error' => 'Student admission not found'], 403);
        }

        // Normalize class name and get course type
        $studentClass = $admission->class;
        $studentCourseType = null;
        
        // Try to get course_type from remarks JSON if it exists
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

        // Check if this PTM is for this student
        $isForStudent = PTMSchedule::where('class_name', $studentClass)
                                   ->where(function($q) use ($studentCourseType) {
                                       if (in_array($studentClass, ['11', '12']) && $studentCourseType) {
                                           $q->where('course_type', $studentCourseType)
                                             ->orWhereNull('course_type');
                                       }
                                   })
                                   ->where('id', $id)
                                   ->exists();
        
        if (!$isForStudent) {
            return response()->json(['error' => 'Unauthorized access to this meeting'], 403);
        }

        if ($ptmSchedule->meeting_mode === 'online' && $ptmSchedule->meeting_link) {
            return response()->json([
                'success' => true,
                'meeting_url' => $ptmSchedule->meeting_link
            ]);
        }

        return response()->json(['error' => 'No meeting link available'], 400);
    }
}
