@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600 mt-2">Here's what's happening in your classes today.</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - 2/3 width -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- SECTION 0: Mark Today's Attendance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">📅 Mark Today's Attendance</h2>
                        <span class="text-sm text-gray-500">{{ date('d M Y, l') }}</span>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $today = now()->toDateString();
                        $teacher = \App\Models\Employee\Employee::where('email', auth()->user()->email)->first();
                        $alreadyMarked = $teacher ? \App\Models\AttendanceRecord::where('employee_code', $teacher->employee_code)
                            ->where('attendance_date', $today)
                            ->first() : null;
                    @endphp
                    @if($alreadyMarked)
                        <div class="text-center">
                            <div class="bg-green-100 text-green-800 px-6 py-3 rounded-lg inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Attendance Marked as Present
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Today's attendance has been recorded</p>
                        </div>
                    @else
                        <div class="text-center">
                            <form action="{{ route('teacher.attendance.mark') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-colors duration-200 inline-flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mark Present
                                </button>
                            </form>
                            <p class="text-sm text-gray-600 mt-2">Click to mark your attendance for today</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SECTION 1: Next Class (Dynamic) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">🔔 Next Class</h2>
                    <span id="nextClassCountdown" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium"></span>
                </div>
                <div id="nextClassInfo">
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-lg font-medium text-gray-600">No upcoming classes today</p>
                        <p class="text-sm text-gray-500 mt-2">Check your schedule for tomorrow's classes</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Today's Schedule (Dynamic) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">📚 Today's Schedule</h2>
                        <button onclick="refreshSchedule()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Refresh</button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="todayScheduleList" class="space-y-4">
                        <!-- Dynamic schedule will be loaded here -->
                    </div>
                    <div id="todayScheduleEmpty" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-lg font-medium">No classes scheduled today</p>
                        <p class="text-sm mt-2">Enjoy your free day!</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Create Assignment -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">📝 Create Assignment</h2>
                <p class="text-gray-600 mb-6">Create homework or assignments for your students.</p>
                <a href="{{ route('teacher.assignments.create') }}" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors text-center block">
                    Create New Assignment
                </a>
            </div>

            <!-- SECTION 4: Notifications -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900">🔔 Notifications</h2>
                        <span id="notificationCount" class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">0</span>
                    </div>
                </div>
                <div class="p-6">
                    <div id="notificationList" class="space-y-3">
                        <!-- Dynamic notifications will be loaded here -->
                    </div>
                    <div id="notificationEmpty" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-lg font-medium">No new notifications</p>
                        <p class="text-sm mt-2">You're all caught up!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - 1/3 width -->
        <div class="space-y-6">
            
            <!-- SECTION 4: Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">📊 Quick Stats</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Today's Classes</span>
                        <span id="todayClassesCount" class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">This Week</span>
                        <span id="weekClassesCount" class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Scheduled</span>
                        <span id="totalClassesCount" class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Pending Tasks</span>
                        <span id="pendingTasksCount" class="font-semibold text-gray-900">0</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Upcoming Holidays -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">🎉 Upcoming Holidays</h2>
                </div>
                <div class="p-6">
                    <div id="holidaysList" class="space-y-3">
                        <!-- Dynamic holidays will be loaded here -->
                    </div>
                    <div id="holidaysEmpty" class="text-center py-4 text-gray-500">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm">No holidays in the next 30 days</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 6: Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">⚡ Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('teacher.assignments.assignment') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors text-center block">
                        📅 Manage Schedule
                    </a>
                    <a href="{{ route('teacher.assignments.create') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors text-center block">
                        📝 Create Assignment
                    </a>
                    <a href="{{ route('teacher.leaves.create') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors text-center block">
                        🏖️ Request Leave
                    </a>
                </div>
            </div>

            <!-- SECTION 7: Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">📈 Recent Activity</h2>
                </div>
                <div class="p-6">
                    <div id="activityList" class="space-y-3">
                        <!-- Dynamic activity will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dynamic Dashboard Data
let schedules = JSON.parse(localStorage.getItem('teacherSchedules')) || [];
let notifications = [];
let activities = [];

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    updateNextClass();
    setInterval(updateNextClass, 60000); // Update every minute
    generateNotifications();
    generateHolidays();
    generateActivity();
    
    // Add test button for leave approval (for testing - remove in production)
    setTimeout(() => {
        // Uncomment below line to test leave approval notification
        // testLeaveApprovalNotification();
    }, 2000);
});

// Test function to simulate admin approving leave (for testing)
function testLeaveApprovalNotification() {
    const leaveDetails = {
        leaveType: 'Sick Leave',
        startDate: '2026-03-01',
        endDate: '2026-03-02'
    };
    addLeaveApprovalNotification(leaveDetails);
}

// Load dashboard data
function loadDashboardData() {
    loadTodaySchedule();
    updateStats();
}

// Load today's schedule
function loadTodaySchedule() {
    const today = new Date().toISOString().split('T')[0];
    const todaySchedules = schedules.filter(s => s.date === today);
    
    const scheduleList = document.getElementById('todayScheduleList');
    const emptyState = document.getElementById('todayScheduleEmpty');
    
    if (todaySchedules.length === 0) {
        scheduleList.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    // Sort by time
    const sortedSchedules = todaySchedules.sort((a, b) => {
        return a.start_time.localeCompare(b.start_time);
    });
    
    scheduleList.innerHTML = sortedSchedules.map(schedule => {
        const now = new Date();
        const scheduleTime = new Date(schedule.date + ' ' + schedule.start_time);
        const endTime = new Date(schedule.date + ' ' + schedule.end_time);
        const isNow = scheduleTime <= now && endTime >= now;
        const isPast = endTime < now;
        const isUpcoming = scheduleTime > now;
        
        let statusBadge = '';
        let bgColor = 'bg-white';
        
        if (isNow) {
            statusBadge = '<span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs font-medium">In Progress</span>';
            bgColor = 'bg-green-50';
        } else if (isUpcoming) {
            statusBadge = '<span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-medium">Upcoming</span>';
            bgColor = 'bg-blue-50';
        } else if (isPast) {
            statusBadge = '<span class="bg-gray-50 text-gray-600 px-2 py-1 rounded text-xs font-medium">Completed</span>';
            bgColor = 'bg-gray-50';
        }
        
        return `
            <div class="${bgColor} rounded-lg p-4 border border-gray-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-gray-900">${schedule.subject}</h3>
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">${schedule.class}</span>
                            ${statusBadge}
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                ${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                ${schedule.room}
                            </span>
                        </div>
                        ${schedule.notes ? `<p class="text-sm text-gray-600 mt-2">${schedule.notes}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Update next class information
function updateNextClass() {
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    
    // Find next upcoming class
    const upcomingSchedules = schedules.filter(s => {
        const scheduleTime = new Date(s.date + ' ' + s.start_time);
        return scheduleTime > now;
    }).sort((a, b) => {
        const timeA = new Date(a.date + ' ' + a.start_time);
        const timeB = new Date(b.date + ' ' + b.start_time);
        return timeA - timeB;
    });
    
    const nextClassInfo = document.getElementById('nextClassInfo');
    const countdownElement = document.getElementById('nextClassCountdown');
    
    if (upcomingSchedules.length > 0) {
        const nextClass = upcomingSchedules[0];
        const classTime = new Date(nextClass.date + ' ' + nextClass.start_time);
        const timeDiff = classTime - now;
        
        if (timeDiff > 0) {
            const hours = Math.floor(timeDiff / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            
            let countdownText = '';
            if (hours > 0) {
                countdownText = `${hours}h ${minutes}m`;
            } else {
                countdownText = `${minutes} min`;
            }
            
            countdownElement.textContent = countdownText;
            
            nextClassInfo.innerHTML = `
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">${nextClass.subject}</h3>
                        <p class="text-gray-700 text-lg mb-1">${nextClass.class}</p>
                        <div class="flex items-center gap-4 text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                ${formatDate(nextClass.date)}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                ${formatTime(nextClass.start_time)} - ${formatTime(nextClass.end_time)}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                ${nextClass.room}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-gray-100 rounded-lg p-3">
                            <p class="text-sm text-gray-600">Starts in</p>
                            <p class="text-2xl font-bold text-gray-900">${countdownText}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    } else {
        countdownElement.textContent = 'No classes';
        nextClassInfo.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-lg font-medium text-gray-600">No upcoming classes</p>
                <p class="text-sm text-gray-500 mt-2">Check your schedule for future classes</p>
            </div>
        `;
    }
}

// Update statistics
function updateStats() {
    const today = new Date().toISOString().split('T')[0];
    const todaySchedules = schedules.filter(s => s.date === today);
    
    // Get current week
    const now = new Date();
    const weekStart = new Date(now.setDate(now.getDate() - now.getDay()));
    const weekEnd = new Date(now.setDate(now.getDate() - now.getDay() + 6));
    
    const weekSchedules = schedules.filter(s => {
        const scheduleDate = new Date(s.date);
        return scheduleDate >= weekStart && scheduleDate <= weekEnd;
    });
    
    document.getElementById('todayClassesCount').textContent = todaySchedules.length;
    document.getElementById('weekClassesCount').textContent = weekSchedules.length;
    document.getElementById('totalClassesCount').textContent = schedules.length;
    document.getElementById('pendingTasksCount').textContent = '3'; // Sample data
}

// Generate notifications
function generateNotifications() {
    // Get leave approval notifications from localStorage
    const leaveNotifications = JSON.parse(localStorage.getItem('leaveNotifications')) || [];
    
    const staticNotifications = [
        {
            id: 1,
            type: 'info',
            title: 'New Assignment Created',
            message: 'Math assignment for 10th class has been created',
            time: '2 hours ago'
        },
        {
            id: 2,
            type: 'warning',
            title: 'Class Schedule Change',
            message: 'Physics lab moved to Room 301 tomorrow',
            time: '5 hours ago'
        },
        {
            id: 3,
            type: 'success',
            title: 'Attendance Recorded',
            message: 'Today\'s attendance has been marked',
            time: '1 day ago'
        }
    ];
    
    // Combine static notifications with leave notifications
    const allNotifications = [...leaveNotifications, ...staticNotifications];
    
    // Sort by time (newest first)
    const sortedNotifications = allNotifications.sort((a, b) => {
        // Convert time strings to timestamps for sorting
        const timeA = getTimeAgoInMinutes(a.time);
        const timeB = getTimeAgoInMinutes(b.time);
        return timeA - timeB;
    });
    
    const notificationList = document.getElementById('notificationList');
    const emptyState = document.getElementById('notificationEmpty');
    const notificationCount = document.getElementById('notificationCount');
    
    if (sortedNotifications.length === 0) {
        notificationList.innerHTML = '';
        emptyState.style.display = 'block';
        notificationCount.textContent = '0';
        return;
    }
    
    emptyState.style.display = 'none';
    notificationCount.textContent = sortedNotifications.length;
    
    notificationList.innerHTML = sortedNotifications.map(notification => {
        const iconColor = notification.type === 'info' ? 'gray' : 
                         notification.type === 'warning' ? 'yellow' : 
                         notification.type === 'success' ? 'green' : 
                         notification.type === 'leave-approved' ? 'green' : 'gray';
        
        const iconSvg = notification.type === 'leave-approved' ? 
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
        
        return `
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg ${notification.type === 'leave-approved' ? 'border-l-4 border-green-500' : ''}">
                <div class="bg-${iconColor}-100 p-2 rounded-full">
                    <svg class="w-4 h-4 text-${iconColor}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${iconSvg}
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">${notification.title}</p>
                    <p class="text-sm text-gray-600">${notification.message}</p>
                    <p class="text-xs text-gray-500 mt-1">${notification.time}</p>
                </div>
                ${notification.type === 'leave-approved' ? '<button onclick="removeNotification(\'' + notification.id + '\')" class="text-gray-400 hover:text-gray-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>' : ''}
            </div>
        `;
    }).join('');
}

// Helper function to convert "X hours ago" to minutes for sorting
function getTimeAgoInMinutes(timeString) {
    if (timeString === 'Just now') return 0;
    if (timeString.includes('minute')) return parseInt(timeString);
    if (timeString.includes('hour')) return parseInt(timeString) * 60;
    if (timeString.includes('day')) return parseInt(timeString) * 60 * 24;
    return 999999; // For very old notifications
}

// Function to remove notification
function removeNotification(notificationId) {
    let leaveNotifications = JSON.parse(localStorage.getItem('leaveNotifications')) || [];
    leaveNotifications = leaveNotifications.filter(n => n.id !== notificationId);
    localStorage.setItem('leaveNotifications', JSON.stringify(leaveNotifications));
    generateNotifications();
}

// Function to add leave approval notification (to be called when admin approves leave)
function addLeaveApprovalNotification(leaveDetails) {
    const leaveNotifications = JSON.parse(localStorage.getItem('leaveNotifications')) || [];
    
    const newNotification = {
        id: 'leave_' + Date.now(),
        type: 'leave-approved',
        title: 'Leave Approved',
        message: `Your leave request for ${leaveDetails.leaveType} from ${leaveDetails.startDate} to ${leaveDetails.endDate} has been approved by admin`,
        time: 'Just now'
    };
    
    leaveNotifications.unshift(newNotification);
    localStorage.setItem('leaveNotifications', JSON.stringify(leaveNotifications));
    generateNotifications();
    
    // Show success notification
    showNotification('Leave approval notification added!');
}

// Generate holidays
function generateHolidays() {
    const holidays = [
        {
            name: 'Republic Day',
            date: '2026-01-26',
            type: 'national',
            passed: new Date('2026-01-26') < new Date()
        },
        {
            name: 'Maha Shivaratri',
            date: '2026-02-26',
            type: 'festival',
            passed: new Date('2026-02-26') < new Date()
        },
        {
            name: 'Holi',
            date: '2026-03-04',
            type: 'festival',
            passed: new Date('2026-03-04') < new Date()
        },
        {
            name: 'Good Friday',
            date: '2026-04-03',
            type: 'religious',
            passed: new Date('2026-04-03') < new Date()
        },
        {
            name: 'Easter Sunday',
            date: '2026-04-05',
            type: 'religious',
            passed: new Date('2026-04-05') < new Date()
        },
        {
            name: 'Dr. B.R. Ambedkar Jayanti',
            date: '2026-04-14',
            type: 'national',
            passed: new Date('2026-04-14') < new Date()
        },
        {
            name: 'Ramadan Eid (Eid ul-Fitr)',
            date: '2026-03-31',
            type: 'festival',
            passed: new Date('2026-03-31') < new Date()
        },
        {
            name: 'Maharashtra Day',
            date: '2026-05-01',
            type: 'state',
            passed: new Date('2026-05-01') < new Date()
        },
        {
            name: 'Bakri Eid (Eid ul-Adha)',
            date: '2026-06-09',
            type: 'festival',
            passed: new Date('2026-06-09') < new Date()
        },
        {
            name: 'Independence Day',
            date: '2026-08-15',
            type: 'national',
            passed: new Date('2026-08-15') < new Date()
        },
        {
            name: 'Ganesh Chaturthi',
            date: '2026-09-09',
            type: 'festival',
            passed: new Date('2026-09-09') < new Date()
        },
        {
            name: 'Mahatma Gandhi Jayanti',
            date: '2026-10-02',
            type: 'national',
            passed: new Date('2026-10-02') < new Date()
        },
        {
            name: 'Dussehra',
            date: '2026-10-02',
            type: 'festival',
            passed: new Date('2026-10-02') < new Date()
        },
        {
            name: 'Diwali',
            date: '2026-10-21',
            type: 'festival',
            passed: new Date('2026-10-21') < new Date()
        },
        {
            name: 'Diwali Padwa',
            date: '2026-10-22',
            type: 'festival',
            passed: new Date('2026-10-22') < new Date()
        },
        {
            name: 'Bhai Dooj',
            date: '2026-10-23',
            type: 'festival',
            passed: new Date('2026-10-23') < new Date()
        },
        {
            name: 'Guru Nanak Jayanti',
            date: '2026-11-05',
            type: 'religious',
            passed: new Date('2026-11-05') < new Date()
        },
        {
            name: 'Christmas',
            date: '2026-12-25',
            type: 'national',
            passed: new Date('2026-12-25') < new Date()
        }
    ];
    
    const holidaysList = document.getElementById('holidaysList');
    const emptyState = document.getElementById('holidaysEmpty');
    
    // Filter upcoming holidays (next 90 days)
    const today = new Date();
    const ninetyDaysLater = new Date(today.getTime() + 90 * 24 * 60 * 60 * 1000);
    
    const upcomingHolidays = holidays.filter(holiday => {
        const holidayDate = new Date(holiday.date);
        return holidayDate >= today && holidayDate <= ninetyDaysLater;
    });
    
    // Sort by date and take only next 2
    const nextTwoHolidays = upcomingHolidays
        .sort((a, b) => new Date(a.date) - new Date(b.date))
        .slice(0, 2);
    
    if (nextTwoHolidays.length === 0) {
        holidaysList.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    holidaysList.innerHTML = nextTwoHolidays.map(holiday => {
        const holidayDate = new Date(holiday.date);
        const daysUntil = Math.ceil((holidayDate - today) / (1000 * 60 * 60 * 24));
        
        let statusBadge = '';
        let statusColor = '';
        
        if (daysUntil === 0) {
            statusBadge = 'Today';
            statusColor = 'red';
        } else if (daysUntil === 1) {
            statusBadge = 'Tomorrow';
            statusColor = 'orange';
        } else if (daysUntil <= 7) {
            statusBadge = `${daysUntil} days`;
            statusColor = 'yellow';
        } else {
            statusBadge = `${daysUntil} days`;
            statusColor = 'gray';
        }
        
        return `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">${holiday.name}</p>
                    <p class="text-sm text-gray-600">${formatDate(holiday.date)} • ${getDayOfWeek(holiday.date)}</p>
                </div>
                <span class="bg-${statusColor}-100 text-${statusColor}-700 px-2 py-1 rounded text-xs font-medium">
                    ${statusBadge}
                </span>
            </div>
        `;
    }).join('');
}

// Helper function to get day of week
function getDayOfWeek(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { weekday: 'long' });
}

// Generate activity
function generateActivity() {
    const activities = [
        {
            action: 'Created Schedule',
            details: 'Math class for 10th grade',
            time: '2 hours ago'
        },
        {
            action: 'Marked Attendance',
            details: 'Present for today',
            time: '4 hours ago'
        },
        {
            action: 'Updated Assignment',
            details: 'Physics homework updated',
            time: '1 day ago'
        }
    ];
    
    const activityList = document.getElementById('activityList');
    
    activityList.innerHTML = activities.map(activity => `
        <div class="flex items-center gap-3 p-2">
            <div class="bg-gray-100 p-1 rounded-full">
                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">${activity.action}</p>
                <p class="text-xs text-gray-600">${activity.details}</p>
            </div>
            <span class="text-xs text-gray-500">${activity.time}</span>
        </div>
    `).join('');
}

// Refresh schedule
function refreshSchedule() {
    schedules = JSON.parse(localStorage.getItem('teacherSchedules')) || [];
    loadTodaySchedule();
    updateStats();
    updateNextClass();
    showNotification('Schedule refreshed successfully!');
}

// Helper functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour > 12 ? hour - 12 : hour === 0 ? 12 : hour;
    return `${displayHour}:${minutes} ${ampm}`;
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
