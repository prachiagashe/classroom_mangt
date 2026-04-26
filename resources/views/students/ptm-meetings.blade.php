@extends('layouts.app')

@section('title', 'PTM Meetings')

@section('page-title', 'PTM Meetings')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Parent-Teacher Meetings</h1>
        <p class="text-gray-600">View and join your upcoming parent-teacher meetings.</p>
        @if($studentClass)
            <div class="mt-2 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    Class {{ $studentClass }}
                </span>
                @if($studentCourseType)
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ strtoupper($studentCourseType) }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Upcoming PTM Meetings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Upcoming Meetings
        </h2>
        
        @if($studentClass && $upcomingMeetings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingMeetings as $meeting)
                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="{{ $meeting->meeting_mode === 'online' ? 'bg-blue-100' : 'bg-green-100' }} p-3 rounded-lg">
                                <svg class="w-6 h-6 {{ $meeting->meeting_mode === 'online' ? 'text-blue-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($meeting->meeting_mode === 'online')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    @endif
                                </svg>
                            </div>
                            <span class="{{ $meeting->meeting_mode === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} text-xs font-medium px-2 py-1 rounded-full">
                                {{ ucfirst($meeting->meeting_mode) }}
                            </span>
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2">Parent-Teacher Meeting</h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $meeting->meeting_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ date('h:i A', strtotime($meeting->start_time)) }} - {{ date('h:i A', strtotime($meeting->end_time)) }}</span>
                            </div>
                            @if($meeting->meeting_mode === 'online' && $meeting->meeting_link)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <span class="text-xs">{{ $meeting->meeting_link }}</span>
                                </div>
                            @endif
                            @if($meeting->meeting_mode === 'offline' && $meeting->meeting_location)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-xs">{{ $meeting->meeting_location }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($meeting->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $meeting->description }}</p>
                        @endif
                        
                        @if($meeting->meeting_mode === 'online')
                            @if($meeting->meeting_link)
                                <button onclick="joinMeeting({{ $meeting->id }})" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-colors font-medium">
                                    Join Meeting
                                </button>
                            @else
                                <div class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg text-center font-medium">
                                    🔗 Link will be available
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium mb-2">
                    @if($studentClass)
                        No upcoming PTM meetings scheduled for your class
                    @else
                        Please update your profile to view PTM meetings
                    @endif
                </p>
                <p class="text-gray-400 text-sm">
                    @if($studentClass)
                        Check back later for new meeting announcements
                    @else
                        Contact administration to set up your class information
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Past PTM Meetings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Past Meetings
        </h2>
        
        @if($studentClass && $pastMeetings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold">Date</th>
                            <th class="text-left py-3 px-4 font-semibold">Subject</th>
                            <th class="text-left py-3 px-4 font-semibold">Teacher</th>
                            <th class="text-left py-3 px-4 font-semibold">Mode</th>
                            <th class="text-left py-3 px-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($pastMeetings as $meeting)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">{{ $meeting->meeting_date->format('d M Y') }}</td>
                                <td class="py-3 px-4 font-medium">{{ $meeting->teacher_name }} PTM</td>
                                <td class="py-3 px-4">{{ $meeting->teacher_name }}</td>
                                <td class="py-3 px-4">
                                    <span class="{{ $meeting->meeting_mode === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} px-2 py-1 rounded-lg text-xs font-medium">
                                        {{ ucfirst($meeting->meeting_mode) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-lg text-xs font-medium">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No past meetings found</p>
            </div>
        @endif
    </div>
</div>

<script>
async function joinMeeting(meetingId) {
    try {
        const response = await fetch(`/student/ptm/join/${meetingId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.open(data.meeting_url, '_blank');
        } else {
            alert(data.error || 'Unable to join meeting');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    }
}

// Check for PTM notifications
document.addEventListener('DOMContentLoaded', function() {
    const notification = sessionStorage.getItem('ptmNotification');
    if (notification) {
        const notificationData = JSON.parse(notification);
        showPTMNotification(notificationData);
        sessionStorage.removeItem('ptmNotification');
    }
});

function showPTMNotification(notification) {
    // Create notification modal
    const notificationModal = document.createElement('div');
    notificationModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    notificationModal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-red-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-red-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">${notification.title}</h3>
                    <p class="text-sm text-gray-600">${notification.message}</p>
                    <p class="text-xs text-gray-500 mt-1">${notification.datetime}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="this.closest('.fixed').remove()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Dismiss
                </button>
                <button onclick="window.location.href='/student/ptm/meetings'" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-colors">
                    View PTM
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(notificationModal);
    
    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (notificationModal.parentNode) {
            notificationModal.remove();
        }
    }, 10000);
}

// Mark notifications as read when student visits PTM page
document.addEventListener('DOMContentLoaded', function() {
    // Mark notification as read by clearing session storage
    sessionStorage.setItem('ptm_notification_seen', 'true');
    
    // Hide notification dot if it exists
    const notificationDot = document.getElementById('notificationDot');
    if (notificationDot) {
        notificationDot.classList.add('hidden');
    }
    
    // Optional: Call API to mark as read
    fetch('/student/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).catch(error => console.log('Error marking notifications as read:', error));
});
</script>

@endsection
