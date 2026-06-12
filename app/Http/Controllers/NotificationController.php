<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->where(function($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)
                  ->orWhereNull('sender_id');
            })
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notification) {
                // Truncate message for dropdown display
                $notification->short_message = \Illuminate\Support\Str::limit($notification->message, 80);
                return $notification;
            });

        $unreadCount = Notification::where('user_id', $user->id)
            ->where(function($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)
                  ->orWhereNull('sender_id');
            })
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $unreadCount
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $user = Auth::user();

        if ($notificationId) {
            Notification::where('id', $notificationId)
                ->where('user_id', $user->id)
                ->update(['is_read' => true]);
        } else {
            // Mark all as read
            Notification::where('user_id', $user->id)
                ->where('sender_id', '!=', $user->id)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Check for new unread notifications and return unread count.
     */
    public function checkNew()
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id)
            ->where(function($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)
                  ->orWhereNull('sender_id');
            })
            ->where('is_read', false);

        $unreadCount = $query->count();
        $notifications = $query->latest()->take(10)->get();

        return response()->json([
            'has_new' => $unreadCount > 0,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }
}
