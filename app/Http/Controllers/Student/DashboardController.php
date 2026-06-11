<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Holiday;
use App\Models\PTMSchedule;
use App\Models\DoubtSession;
use App\Models\Notification;
use App\Models\FeePayment;
use Carbon\Carbon;
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
        
        // Format Student Course Type dynamically
        $courseType = 'REGULAR';
        if ($admission) {
            if (!empty($admission->course_type)) {
                $courseType = $admission->course_type;
            } elseif ($admission->enquiry && !empty($admission->enquiry->course)) {
                $courses = is_array($admission->enquiry->course) ? $admission->enquiry->course : [$admission->enquiry->course];
                $courseType = !empty($courses) ? implode(', ', $courses) : 'REGULAR';
            } else {
                $remarks = is_string($admission->remarks) ? json_decode($admission->remarks, true) : $admission->remarks;
                if (isset($remarks['original_data']['course'])) {
                    $courses = is_array($remarks['original_data']['course']) ? $remarks['original_data']['course'] : [$remarks['original_data']['course']];
                    $courseType = !empty($courses) ? implode(', ', $courses) : 'REGULAR';
                }
            }
        }
        $courseType = strtoupper($courseType);

        // Get attendance statistics from student_attendence table
        $attendancePercentage = 100.0;
        $attendanceRank = 1;
        $progressStatus = 'Excellent';
        $totalPresent = 0;
        $totalAbsent = 0;
        
        $currentMonthName = Carbon::now()->format('F Y');
        
        if ($rollNumber) {
            $attendanceRecords = \App\Models\StudentAttendence::where('roll_no', $rollNumber)->get();
            if ($attendanceRecords->isNotEmpty()) {
                $totalPresent = $attendanceRecords->sum('total_p');
                $totalAbsent = $attendanceRecords->sum('total_a');
                $totalDays = $totalPresent + $totalAbsent;
                if ($totalDays > 0) {
                    $attendancePercentage = round(($totalPresent / $totalDays) * 100, 1);
                } else {
                    $attendancePercentage = round($attendanceRecords->avg('percentage'), 1);
                }
            } else {
                $attendancePercentage = 92.5; 
            }
            
            // Calculate Class Rank based on attendance
            $classAdmissions = Admission::where('class', $studentClass)->pluck('roll_number');
            if ($classAdmissions->count() > 1) {
                $peers = \App\Models\StudentAttendence::whereIn('roll_no', $classAdmissions)
                    ->get()
                    ->groupBy('roll_no');
                
                $peerPercentages = [];
                foreach ($peers as $peerRoll => $records) {
                    $pSum = $records->sum('total_p');
                    $aSum = $records->sum('total_a');
                    $tot = $pSum + $aSum;
                    $peerPercentages[$peerRoll] = $tot > 0 ? ($pSum / $tot) * 100 : 92.5;
                }
                
                arsort($peerPercentages);
                $rankIndex = 1;
                foreach ($peerPercentages as $peerRoll => $pct) {
                    if ($peerRoll == $rollNumber) {
                        $attendanceRank = $rankIndex;
                        break;
                    }
                    $rankIndex++;
                }
            }
        } else {
            $attendancePercentage = 92.5;
        }

        // Determine progress status based on attendance
        if ($attendancePercentage >= 90) {
            $progressStatus = 'Excellent';
        } elseif ($attendancePercentage >= 75) {
            $progressStatus = 'Good';
        } else {
            $progressStatus = 'Needs Improvement';
        }

        // Get today's day name and lectures
        $todayDay = Carbon::now()->format('l');
        $todayTimetable = collect();
        
        if ($classNumber) {
            $todayTimetable = Timetable::byClass($classNumber)
                ->with(['subject'])
                ->where('day', $todayDay)
                ->where('day', '!=', 'period_timing')
                ->published()
                ->orderBy('period_number')
                ->get();
            
            $periodTimings = Timetable::where('class_name', $classNumber)
                ->where('day', 'period_timing')
                ->published()
                ->get()
                ->keyBy('period_number');
            
            $todayTimetable->transform(function($entry) use ($classNumber, $periodTimings) {
                $entry->room = "Room " . ($classNumber * 100 + $entry->period_number);
                
                $timing = $periodTimings->get($entry->period_number);
                $entry->start_time = $timing ? $timing->start_time : null;
                $entry->end_time = $timing ? $timing->end_time : null;
                
                if ($entry->start_time && $entry->end_time) {
                    $nowTime = Carbon::now()->format('H:i:s');
                    if ($nowTime >= $entry->start_time && $nowTime <= $entry->end_time) {
                        $entry->lecture_status = 'Ongoing';
                    } elseif ($nowTime < $entry->start_time) {
                        $entry->lecture_status = 'Upcoming';
                    } else {
                        $entry->lecture_status = 'Completed';
                    }
                } else {
                    $entry->lecture_status = 'Scheduled';
                }
                return $entry;
            });
        }

        // Get Assignments and calculate Completed/Pending counts
        $pendingAssignments = collect();
        $completedAssignmentsCount = 0;
        $pendingAssignmentsCount = 0;
        
        if ($classNumber) {
            $allAssignments = Assignment::where('class_id', $classNumber)
                ->orWhere('class_id', $studentClass)
                ->with(['teacher', 'submissions' => function($query) use ($user) {
                    $query->where('student_id', $user->id);
                }])
                ->orderBy('due_date', 'asc')
                ->get();
                
            foreach ($allAssignments as $assignment) {
                $submission = $assignment->submissions->first();
                if ($submission) {
                    $assignment->status = ucfirst($submission->status);
                    $completedAssignmentsCount++;
                } else {
                    if (now()->gt(Carbon::parse($assignment->due_date))) {
                        $assignment->status = 'Overdue';
                    } else {
                        $assignment->status = 'Pending';
                    }
                    $pendingAssignmentsCount++;
                    $pendingAssignments->push($assignment);
                }
            }
        }

        // Doubt Sessions
        $upcomingDoubtSessions = collect();
        if ($classNumber) {
            $numericClass = preg_replace('/[^0-9]/', '', $studentClass);
            $allSessions = DoubtSession::where('status', 'published')
                ->where('class_name', $numericClass)
                ->with(['subject', 'teacher'])
                ->orderBy('session_date')
                ->orderBy('start_time')
                ->get();
            
            $now = Carbon::now();
            $upcomingDoubtSessions = $allSessions->filter(function($session) use ($now) {
                try {
                    $sessionEndDateTime = Carbon::parse($session->session_date . ' ' . $session->end_time);
                    return $sessionEndDateTime->gt($now);
                } catch (\Exception $e) {
                    return true;
                }
            })->take(3);
        }

        // Combined notification / event timeline
        $combinedNotifications = collect();
        
        $upcomingHolidays = Holiday::where('holiday_date', '>=', now()->toDateString())
            ->orderBy('holiday_date', 'asc')
            ->take(3)
            ->get()
            ->map(function($holiday) {
                return [
                    'title' => 'Holiday Declared',
                    'message' => "Holiday on " . Carbon::parse($holiday->holiday_date)->format('M d, Y') . " - " . $holiday->reason,
                    'type' => 'holiday',
                    'date' => Carbon::parse($holiday->holiday_date),
                    'meta' => $holiday->reason,
                ];
            });
            
        $upcomingPtms = collect();
        if ($classNumber) {
            $ptmQuery = PTMSchedule::where('class_name', $classNumber);
            if (in_array($classNumber, ['11', '12']) && isset($courseType)) {
                $ptmQuery->where(function($q) use ($courseType) {
                    $q->where('course_type', $courseType)
                      ->orWhereNull('course_type');
                });
            }
            
            $upcomingPtms = $ptmQuery->where('status', 'scheduled')
                ->where('meeting_date', '>=', now()->toDateString())
                ->orderBy('meeting_date', 'asc')
                ->take(3)
                ->get()
                ->map(function($ptm) {
                    $meetingTime = $ptm->start_time ? Carbon::parse('1970-01-01 ' . $ptm->start_time)->format('h:i A') : '';
                    return [
                        'title' => 'Upcoming PTM Meeting',
                        'message' => "Parent-Teacher Meeting scheduled for " . Carbon::parse($ptm->meeting_date)->format('M d, Y') . " at " . $meetingTime,
                        'type' => 'ptm',
                        'date' => Carbon::parse($ptm->meeting_date),
                        'meta' => $ptm->description ?? 'Parent-Teacher Interaction',
                    ];
                });
        }
        
        $userNotifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function($notif) {
                return [
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'type' => $notif->type ?? 'general',
                    'date' => $notif->created_at,
                    'meta' => null,
                ];
            });
            
        $combinedNotifications = $combinedNotifications
            ->concat($upcomingHolidays)
            ->concat($upcomingPtms)
            ->concat($userNotifications)
            ->sortByDesc('date')
            ->values();

        // Unique Class Teachers list for "Contact Teacher" modal
        $classTeachers = collect();
        if ($classNumber) {
            $subjects = Subject::where('class_name', $classNumber)
                ->where('is_active', true)
                ->get();
                
            $classTeachers = $subjects->map(function($subject) {
                return [
                    'name' => $subject->teacher_name,
                    'email' => $subject->teacher_email,
                    'subject' => $subject->name,
                ];
            })->filter(function($t) {
                return !empty($t['name']);
            })->unique('name')->values();
        }

        // Calculate fee-related variables
        $totalFee = $admission ? $admission->total_fee : 0;
        $paidAmount = $admission ? $admission->paid_amount : 0;
        $pendingAmount = $admission ? ($totalFee - $paidAmount) : 0;
        $nextDueDate = $admission ? Carbon::parse($admission->admission_date)->addDays(30) : null;
        $isOverdue = $admission && $pendingAmount > 0 && Carbon::parse($admission->admission_date)->addDays(30)->isPast();
        $overdueDays = $admission && $isOverdue ? Carbon::parse($admission->admission_date)->diffInDays(Carbon::now()) : 0;
        $isDueSoon = $admission && !$isOverdue && $pendingAmount > 0;
        $paymentStatus = $admission ? ($pendingAmount > 0 ? 'pending' : 'paid') : 'not_admitted';

        // Retrieve latest payment record ID for Download Receipt action
        $latestPaymentId = null;
        if ($admission) {
            $latestPayment = $admission->feePayments()->latest()->first();
            if ($latestPayment) {
                $latestPaymentId = $latestPayment->id;
            }
        }

        $stats = [
            'total_subjects' => $classNumber ? Subject::where('class_name', $classNumber)->count() : 0,
            'attendance_rate' => $attendancePercentage,
            'assignments_pending' => $pendingAssignmentsCount,
            'completed_assignments' => $completedAssignmentsCount,
            'pending_assignments' => $pendingAssignmentsCount,
            'attendance_percentage' => $attendancePercentage,
        ];

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
            'paymentStatus',
            'courseType',
            'attendancePercentage',
            'attendanceRank',
            'progressStatus',
            'todayTimetable',
            'pendingAssignments',
            'upcomingDoubtSessions',
            'combinedNotifications',
            'classTeachers',
            'latestPaymentId'
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
        // Normalize class name if needed (e.g., "6th" -> "6", "5th Class" -> "5")
        if (preg_match('/^(\d+)/', $className, $matches)) {
            $className = $matches[1];
        }

        $subjects = Subject::where('class_name', $className)
            ->where('is_active', true)
            ->get(['id', 'name', 'code', 'teacher_name']);
        
        return response()->json($subjects);
    }
}
