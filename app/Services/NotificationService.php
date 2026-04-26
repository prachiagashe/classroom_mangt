<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send a notification to a specific user.
     */
    public static function notifyUser(int $userId, string $title, string $message, string $type = 'general', array $data = []): Notification
    {
        // Add default redirect URL if not provided
        if (!isset($data['redirect_url'])) {
            $data['redirect_url'] = self::getDefaultRedirectUrl($type);
        }

        return Notification::create([
            'user_id' => $userId,
            'sender_id' => auth()->id(),
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Send a notification to all students in a given class.
     * Matches students via Admission.email → User.email where role = 'student'.
     *
     * @param string $className  The class name from PTM/DoubtSession (e.g. "11", "10th", "Class 11")
     */
    public static function notifyStudentsByClass(string $className, string $title, string $message, string $type = 'general', array $data = []): int
    {
        // Normalize class number (extract digits from "10th", "Class 11", etc.)
        $classNumber = $className;
        if (preg_match('/(\d+)/', $className, $matches)) {
            $classNumber = $matches[1];
        }

        // Find all admissions matching this class (handle various formats)
        $admissionEmails = Admission::where(function ($query) use ($className, $classNumber) {
            $query->where('class', $className)
                  ->orWhere('class', $classNumber)
                  ->orWhere('class', $classNumber . 'th')
                  ->orWhere('class', 'Class ' . $classNumber);
        })
        ->whereNotNull('email')
        ->pluck('email')
        ->unique();

        // Find matching student users
        $studentUsers = User::where('role', 'student')
            ->whereIn('email', $admissionEmails)
            ->get();

        $count = 0;
        $senderId = auth()->id();
        
        // Add default redirect URL if not provided
        if (!isset($data['redirect_url'])) {
            $data['redirect_url'] = self::getDefaultRedirectUrl($type);
        }

        foreach ($studentUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'sender_id' => $senderId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => $data,
                'is_read' => false,
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Send a notification to all admin users.
     */
    public static function notifyAdmins(string $title, string $message, string $type = 'general', array $data = []): int
    {
        $currentUserId = auth()->id();
        $admins = User::where('role', 'admin')
            ->where('id', '!=', $currentUserId)
            ->get();
            
        $senderId = auth()->id();
        $count = 0;
        
        // Add default redirect URL if not provided
        if (!isset($data['redirect_url'])) {
            $data['redirect_url'] = self::getDefaultRedirectUrl($type);
        }

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'sender_id' => $senderId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => $data,
                'is_read' => false,
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Send a notification to a student by their admission email.
     */
    public static function notifyStudentByEmail(string $email, string $title, string $message, string $type = 'general', array $data = []): ?Notification
    {
        $user = User::where('role', 'student')->where('email', $email)->first();

        if (!$user) {
            return null;
        }

        return self::notifyUser($user->id, $title, $message, $type, $data);
    }

    /**
     * Get default redirect URL based on notification type.
     */
    private static function getDefaultRedirectUrl(string $type): string
    {
        switch ($type) {
            case 'ptm':
                return '/student/ptm/meetings';
            case 'doubt':
                return '/student/doubt-sessions';
            case 'timetable':
                return '/student/timetable';
            case 'subject':
                return '/student/courses';
            case 'leave_request':
                return '/admin/leave';
            case 'leave_status':
                return '/teacher/leaves';
            case 'fees':
                return '/student/fees';
            default:
                return '/student/dashboard';
        }
    }
}
