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

    <!-- SECTION: Class Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach($assignedClasses as $class)
            <div class="bg-white rounded-2xl shadow-md border border-gray-150 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold uppercase">Class {{ $class['name'] }}</h2>
                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <p class="text-blue-100 text-sm mt-1">Manage coursework & submissions</p>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4 text-center">
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <span class="text-2xl font-black text-gray-800 block">{{ $class['student_count'] }}</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Students</span>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <span class="text-2xl font-black text-gray-800 block">{{ $class['present_students'] }}</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Present Today</span>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 col-span-2 px-4 py-3 text-left">
                        <span class="text-xs font-bold text-gray-500 block uppercase tracking-wider mb-1">Next Class</span>
                        <div id="next-class-{{ $class['name'] }}">
                            <span class="text-sm font-black text-gray-800 block">None Scheduled</span>
                            <span class="text-xs text-gray-500 block">Check schedule later</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Attendance Calendar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    Attendance Calendar
                </h2>
                <div class="flex items-center gap-2">
                    <button onclick="changeAttendanceMonth(-1)" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <span id="currentAttendanceMonth" class="text-sm font-bold text-gray-700 min-w-[100px] text-center"></span>
                    <button onclick="changeAttendanceMonth(1)" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <!-- Calendar Legend -->
                <div class="flex flex-wrap gap-4 mb-4 text-xs font-bold justify-center">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span> PRESENT</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> ABSENT</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-yellow-500 rounded-full"></span> LEAVE</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span> HOLIDAY</div>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-xs font-bold text-gray-400 py-1 uppercase tracking-wider">{{ $day }}</div>
                    @endforeach
                </div>
                <div id="attendanceCalendarGrid" class="grid grid-cols-7 gap-1 text-center">
                    <!-- Days will be inserted by JS -->
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
            <h2 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider text-center">⚡ Quick Links</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('teacher.assignments.assignment') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs uppercase tracking-wider text-center py-3 rounded-xl transition-all">
                    Schedules
                </a>
                <a href="{{ route('teacher.leaves.create') }}" class="bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold text-xs uppercase tracking-wider text-center py-3 rounded-xl transition-all">
                    Request Leave
                </a>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Create Assignment Modal -->
<div id="assignmentModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50 animate-fade-in backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-150 w-full max-w-2xl mx-4 overflow-hidden transform transition-all duration-300 scale-100">
        <div class="p-6 border-b border-gray-150 flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">📝 Create Assignment</h2>
                <p class="text-xs text-gray-500 mt-1">Directly publish coursework guidelines to students.</p>
            </div>
            <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('teacher.assignments.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Class Selection -->
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Class *</label>
                    <select name="class" required class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Selection -->
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Section *</label>
                    <select name="section" required class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}">{{ $section }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Selection -->
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Subject *</label>
                    <select name="subject" required class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Assignment Title *</label>
                <input type="text" name="title" required
                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., Physics Chapter 5 Assignment">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Description *</label>
                <textarea name="description" rows="3" required
                          class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Provide detailed instructions for the assignment..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Due Date -->
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Due Date *</label>
                    <input type="date" name="due_date" required
                           class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Attachment -->
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">PDF Attachment *</label>
                    <input type="file" name="attachment" accept=".pdf" required
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 justify-end bg-gray-50 p-4 -mx-6 -mb-6 border-t border-gray-150">
                <button type="button" onclick="closeAssignmentModal()"
                        class="bg-gray-200 text-gray-800 font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Publish Assignment
                </button>
            </div>
        </form>
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
    updateClassCardsNextClass();
}

function updateClassCardsNextClass() {
    const now = new Date();
    const today = now.toISOString().split('T')[0];
    
    // Group schedules by class
    const classSchedules = {};
    schedules.filter(s => s.date === today).forEach(s => {
        if (!classSchedules[s.class]) {
            classSchedules[s.class] = [];
        }
        classSchedules[s.class].push(s);
    });
    
    Object.keys(classSchedules).forEach(className => {
        const classScheds = classSchedules[className];
        
        // Filter upcoming
        const upcoming = classScheds.filter(s => {
            const scheduleTime = new Date(s.date + ' ' + s.start_time);
            return scheduleTime > now;
        }).sort((a, b) => a.start_time.localeCompare(b.start_time));
        
        // Find ongoing
        const ongoing = classScheds.find(s => {
            const start = new Date(s.date + ' ' + s.start_time);
            const end = new Date(s.date + ' ' + s.end_time);
            return start <= now && end >= now;
        });
        
        const cardEl = document.getElementById(`next-class-${className}`);
        if (cardEl) {
            if (ongoing) {
                cardEl.innerHTML = `
                    <span class="text-sm font-black text-blue-600 block">${ongoing.subject} <span class="text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded-full uppercase ml-1 animate-pulse">Ongoing</span></span>
                    <span class="text-xs text-gray-600 block">${formatTime(ongoing.start_time)} – ${formatTime(ongoing.end_time)}</span>
                `;
            } else if (upcoming.length > 0) {
                const next = upcoming[0];
                cardEl.innerHTML = `
                    <span class="text-sm font-black text-gray-800 block">${next.subject}</span>
                    <span class="text-xs text-gray-600 block">${formatTime(next.start_time)} – ${formatTime(next.end_time)}</span>
                `;
            } else {
                cardEl.innerHTML = `
                    <span class="text-sm font-bold text-gray-400 block">No more classes</span>
                    <span class="text-xs text-gray-400 block">Done for today</span>
                `;
            }
        }
    });
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

function openAssignmentModal() {
    document.getElementById('assignmentModal').classList.remove('hidden');
}

function closeAssignmentModal() {
    document.getElementById('assignmentModal').classList.add('hidden');
}
// Attendance Calendar JS
let attendanceDate = new Date();
const teacherRecords = @json($teacherRecords);
const holidays = @json($holidays);

function changeAttendanceMonth(direction) {
    attendanceDate.setMonth(attendanceDate.getMonth() + direction);
    updateAttendanceCalendar();
}

function updateAttendanceCalendar() {
    const year = attendanceDate.getFullYear();
    const month = attendanceDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    
    const firstDayIndex = firstDay.getDay();
    const lastDayIndex = lastDay.getDay();
    const nextDays = 7 - lastDayIndex - 1;
    
    document.getElementById('currentAttendanceMonth').textContent = attendanceDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    
    let daysHTML = '';
    
    // Previous month days
    for (let x = firstDayIndex; x > 0; x--) {
        daysHTML += `<div class="text-xs text-gray-300 py-2 border border-gray-50 bg-gray-50/50">${prevLastDay.getDate() - x + 1}</div>`;
    }
    
    // Current month days
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        
        // Find attendance record
        const record = teacherRecords.find(r => r.attendance_date === dateStr);
        // Find holiday
        const holiday = holidays.find(h => h.holiday_date === dateStr);
        
        let styleStr = 'background-color: white; color: #1f2937;';
        let statusIcon = '';
        let tooltipText = '';

        if (holiday) {
            styleStr = 'background-color: #eff6ff; color: #1e40af;';
            statusIcon = '<span style="color: #2563eb; font-size: 11px; font-weight: bold;">H</span>';
            tooltipText = `Holiday: ${holiday.reason || 'Public Holiday'}`;
        } else if (record) {
            if (record.status === 'present') {
                styleStr = 'background-color: #ecfdf5; color: #065f46;';
                statusIcon = '<span style="color: #059669; font-size: 11px; font-weight: bold;">✔</span>';
                tooltipText = 'Present';
            } else if (record.status === 'absent') {
                styleStr = 'background-color: #fef2f2; color: #991b1b;';
                statusIcon = '<span style="color: #dc2626; font-size: 11px; font-weight: bold;">✖</span>';
                tooltipText = 'Absent';
            } else if (record.status === 'leave') {
                styleStr = 'background-color: #fffbeb; color: #92400e;';
                statusIcon = '<span style="color: #d97706; font-size: 11px; font-weight: bold;">L</span>';
                tooltipText = 'On Leave';
            }
        }
        
        const isToday = new Date().toDateString() === new Date(year, month, i).toDateString();
        const todayBorder = isToday ? 'border: 2px solid #3b82f6;' : 'border: 1px solid #f3f4f6;';
        
        daysHTML += `
            <div style="${styleStr} ${todayBorder} border-radius: 8px;" 
                 class="text-xs p-1.5 relative flex flex-col justify-between h-11 cursor-pointer transition-all hover:scale-105" 
                 title="${tooltipText}">
                <span style="font-weight: 800;">${i}</span>
                <div class="flex justify-end items-end">${statusIcon}</div>
            </div>
        `;
    }
    
    // Next month days
    for (let j = 1; j <= nextDays; j++) {
        daysHTML += `<div class="text-xs text-gray-300 py-2 border border-gray-50 bg-gray-50/50">${j}</div>`;
    }
    
    document.getElementById('attendanceCalendarGrid').innerHTML = daysHTML;
}

// Call initially
document.addEventListener('DOMContentLoaded', function() {
    updateAttendanceCalendar();
});
</script>
@endsection
