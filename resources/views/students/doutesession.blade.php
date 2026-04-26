@extends('layouts.app')

@section('title', 'Doubt Sessions')

@section('page-title', 'Doubt Sessions')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Doubt Sessions</h1>
        <p class="text-gray-600">Join doubt clearing sessions scheduled for your class.</p>
        @if($studentClass)
            <div class="mt-2 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    Class {{ $studentClass }}
                </span>
            </div>
        @endif
    </div>

    <!-- Upcoming Doubt Sessions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Upcoming Sessions
        </h2>
        
        @if($upcomingSessions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingSessions as $session)
                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                                Upcoming
                            </span>
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $session->subject->name }} - Doubt Session</h3>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>{{ $session->subject->name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $session->teacher->name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $session->formatted_date }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $session->formatted_time }}</span>
                            </div>
                        </div>
                        
                        @if($session->description)
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($session->description, 100) }}</p>
                        @endif
                        
                        <button onclick="viewSessionDetails({{ $session->id }})" 
                                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-2 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-colors font-medium">
                            View Details
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium mb-2">No upcoming doubt sessions</p>
                <p class="text-gray-400 text-sm">Check back later for new doubt session announcements</p>
            </div>
        @endif
    </div>

    <!-- Past Doubt Sessions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Past Sessions
        </h2>
        
        @if($pastSessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold">Date</th>
                            <th class="text-left py-3 px-4 font-semibold">Title</th>
                            <th class="text-left py-3 px-4 font-semibold">Subject</th>
                            <th class="text-left py-3 px-4 font-semibold">Teacher</th>
                            <th class="text-left py-3 px-4 font-semibold">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($pastSessions as $session)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">{{ $session->formatted_date }}</td>
                                <td class="py-3 px-4 font-medium">{{ $session->subject->name }} - Doubt Session</td>
                                <td class="py-3 px-4">{{ $session->subject->name }}</td>
                                <td class="py-3 px-4">{{ $session->teacher->name }}</td>
                                <td class="py-3 px-4">{{ $session->formatted_time }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No past sessions found</p>
            </div>
        @endif
    </div>
</div>

<script>
function viewSessionDetails(sessionId) {
    // You can implement this to show a modal or navigate to details page
    console.log('View session details:', sessionId);
    // For now, just show an alert
    alert('Session details feature coming soon!');
}

// Check for Doubt Session notifications
document.addEventListener('DOMContentLoaded', function() {
    const notification = sessionStorage.getItem('doubtNotification');
    if (notification) {
        const notificationData = JSON.parse(notification);
        showDoubtNotification(notificationData);
        sessionStorage.removeItem('doubtNotification');
    }
    
    // Hide notification dot if it exists natively
    const notificationDot = document.getElementById('notificationDot');
    if (notificationDot) {
        notificationDot.classList.add('hidden');
    }
});

function showDoubtNotification(notification) {
    // Create notification modal
    const notificationModal = document.createElement('div');
    notificationModal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    notificationModal.innerHTML = `
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-purple-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
</script>
@endsection