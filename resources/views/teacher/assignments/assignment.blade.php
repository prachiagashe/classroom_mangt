@extends('layouts.app')

@section('title', 'My Schedule & Assignments')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Schedule & Assignments</h1>
        <div class="flex gap-3">
            <button onclick="openScheduleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                ➕ Add Schedule
            </button>
            <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Classes</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $employee->assigned_classes ? count(explode(', ', $employee->assigned_classes)) : 0 }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Total Subjects</p>
                    <p class="text-2xl font-bold text-green-900">{{ $employee->assigned_subjects ? count(explode(', ', $employee->assigned_subjects)) : 0 }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Scheduled Classes</p>
                    <p class="text-2xl font-bold text-purple-900" id="scheduledCount">0</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">Today's Classes</p>
                    <p class="text-2xl font-bold text-orange-900" id="todayCount">0</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- My Scheduled Classes -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">My Scheduled Classes</h2>
            <p class="text-sm text-gray-600 mt-1">Classes you have scheduled</p>
        </div>
        <div class="p-6">
            <div id="scheduleList" class="space-y-4">
                <!-- Schedules will be dynamically added here -->
            </div>
            
            <div id="emptyState" class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-lg font-medium">No classes scheduled</p>
                <p class="text-sm mt-2">Start by adding your first class schedule.</p>
                <button onclick="openScheduleModal()" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create Your First Schedule
                </button>
            </div>
        </div>
    </div>

    <!-- Subject-wise Assignment Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Classes Assignment -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Assigned Classes</h2>
                <p class="text-sm text-gray-600 mt-1">Classes you are teaching</p>
            </div>
            <div class="p-6">
                @if($employee->assigned_classes)
                    <div class="flex flex-wrap gap-3">
                        @foreach(explode(', ', $employee->assigned_classes) as $class)
                            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $class }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-lg font-medium">No classes assigned</p>
                        <p class="text-sm mt-2">You haven't been assigned to any classes yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Subjects Assignment -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Assigned Subjects</h2>
                <p class="text-sm text-gray-600 mt-1">Subjects you are teaching</p>
            </div>
            <div class="p-6">
                @if($employee->assigned_subjects)
                    <div class="flex flex-wrap gap-3">
                        @foreach(explode(', ', $employee->assigned_subjects) as $subject)
                            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $subject }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C12 2.253 4 2.253 6 4.253v13c0 5.747-2.253 8-8 8v13c0 5.747-2.253 8 8 8v13c0 5.747-2.253 8-8-8z"/>
                        </svg>
                        <p class="text-lg font-medium">No subjects assigned</p>
                        <p class="text-sm mt-2">You haven't been assigned to any subjects yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<x-modal id="scheduleModal" title="Class Schedule" :show="false" maxWidth="3xl">
    <form id="scheduleForm" class="space-y-6">
        <input type="hidden" id="scheduleId" value="">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Subject Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                    <select id="subject" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Subject</option>
                        @if($employee->assigned_subjects)
                            @foreach(explode(', ', $employee->assigned_subjects) as $subject)
                                <option value="{{ $subject }}">{{ $subject }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Class Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class <span class="text-red-500">*</span></label>
                    <select id="class" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Class</option>
                        @if($employee->assigned_classes)
                            @foreach(explode(', ', $employee->assigned_classes) as $class)
                                <option value="{{ $class }}">{{ $class }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Date Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                    <input type="date" id="date" required 
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Room Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Number <span class="text-red-500">*</span></label>
                    <input type="text" id="room" required 
                           placeholder="e.g., Room 201, Lab 102"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Start Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" id="start_time" required 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                    <input type="time" id="end_time" required 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Class Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class Type <span class="text-red-500">*</span></label>
                    <select id="class_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Type</option>
                        <option value="lecture">Lecture</option>
                        <option value="lab">Lab Session</option>
                        <option value="tutorial">Tutorial</option>
                        <option value="exam">Exam/Test</option>
                        <option value="assignment">Assignment Review</option>
                    </select>
                </div>

                <!-- Recurring Pattern -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recurring Pattern</label>
                    <select id="recurring" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="none">One-time Class</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
            <textarea id="notes" rows="3" 
                      placeholder="Any additional information about this class..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6">
            <button type="button" onclick="closeModal('scheduleModal')" 
                    class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium order-2 sm:order-1">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium order-1 sm:order-2 shadow-lg">
                <span id="submitButtonText">Create Schedule</span>
            </button>
        </div>
    </form>
</x-modal>

<script>
// Schedule management using localStorage
let schedules = JSON.parse(localStorage.getItem('teacherSchedules')) || [];
let editingId = null;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    renderSchedules();
    updateStats();
});

// Open schedule modal
function openScheduleModal(id = null) {
    const form = document.getElementById('scheduleForm');
    const submitButton = document.getElementById('submitButtonText');
    
    // Reset form
    form.reset();
    
    if (id) {
        // Edit mode
        const schedule = schedules.find(s => s.id === id);
        if (schedule) {
            document.getElementById('scheduleId').value = schedule.id;
            document.getElementById('subject').value = schedule.subject;
            document.getElementById('class').value = schedule.class;
            document.getElementById('date').value = schedule.date;
            document.getElementById('room').value = schedule.room;
            document.getElementById('start_time').value = schedule.start_time;
            document.getElementById('end_time').value = schedule.end_time;
            document.getElementById('class_type').value = schedule.class_type;
            document.getElementById('recurring').value = schedule.recurring;
            document.getElementById('notes').value = schedule.notes || '';
            
            submitButton.textContent = 'Update Schedule';
            editingId = id;
        }
    } else {
        // Create mode
        document.getElementById('scheduleId').value = '';
        submitButton.textContent = 'Create Schedule';
        editingId = null;
    }
    
    openModal('scheduleModal');
}

// Handle form submission
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        id: editingId || Date.now().toString(),
        subject: document.getElementById('subject').value,
        class: document.getElementById('class').value,
        date: document.getElementById('date').value,
        room: document.getElementById('room').value,
        start_time: document.getElementById('start_time').value,
        end_time: document.getElementById('end_time').value,
        class_type: document.getElementById('class_type').value,
        recurring: document.getElementById('recurring').value,
        notes: document.getElementById('notes').value,
        created_at: editingId ? schedules.find(s => s.id === editingId).created_at : new Date().toISOString()
    };
    
    if (editingId) {
        // Update existing schedule
        const index = schedules.findIndex(s => s.id === editingId);
        if (index !== -1) {
            schedules[index] = formData;
        }
    } else {
        // Add new schedule
        schedules.push(formData);
    }
    
    // Save to localStorage
    localStorage.setItem('teacherSchedules', JSON.stringify(schedules));
    
    // Close modal and refresh
    closeModal('scheduleModal');
    renderSchedules();
    updateStats();
    
    // Show success message
    showNotification(editingId ? 'Schedule updated successfully!' : 'Schedule created successfully!');
});

// Delete schedule
function deleteSchedule(id) {
    if (confirm('Are you sure you want to delete this schedule?')) {
        schedules = schedules.filter(s => s.id !== id);
        localStorage.setItem('teacherSchedules', JSON.stringify(schedules));
        renderSchedules();
        updateStats();
        showNotification('Schedule deleted successfully!');
    }
}

// Render schedules
function renderSchedules() {
    const scheduleList = document.getElementById('scheduleList');
    const emptyState = document.getElementById('emptyState');
    
    if (schedules.length === 0) {
        scheduleList.innerHTML = '';
        emptyState.style.display = 'block';
        return;
    }
    
    emptyState.style.display = 'none';
    
    // Sort schedules by date and time
    const sortedSchedules = [...schedules].sort((a, b) => {
        const dateA = new Date(a.date + ' ' + a.start_time);
        const dateB = new Date(b.date + ' ' + b.start_time);
        return dateA - dateB;
    });
    
    scheduleList.innerHTML = sortedSchedules.map(schedule => {
        const isToday = schedule.date === new Date().toISOString().split('T')[0];
        const isPast = new Date(schedule.date + ' ' + schedule.end_time) < new Date();
        
        return `
            <div class="border-l-4 ${isToday ? 'border-blue-500 bg-blue-50' : isPast ? 'border-gray-300 bg-gray-50' : 'border-gray-300 bg-white'} p-4 rounded-lg">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-gray-900">${schedule.subject}</h3>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">${schedule.class}</span>
                            <span class="bg-${getClassTypeColor(schedule.class_type)}-100 text-${getClassTypeColor(schedule.class_type)}-800 px-2 py-1 rounded text-xs font-medium">${getClassTypeLabel(schedule.class_type)}</span>
                            ${schedule.recurring !== 'none' ? `<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">${getRecurringLabel(schedule.recurring)}</span>` : ''}
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                ${formatDate(schedule.date)}
                            </span>
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
                    <div class="flex gap-2 ml-4">
                        <button onclick="openScheduleModal('${schedule.id}')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</button>
                        <button onclick="deleteSchedule('${schedule.id}')" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Update statistics
function updateStats() {
    const today = new Date().toISOString().split('T')[0];
    const todaySchedules = schedules.filter(s => s.date === today);
    
    document.getElementById('scheduledCount').textContent = schedules.length;
    document.getElementById('todayCount').textContent = todaySchedules.length;
}

// Helper functions
function getClassTypeColor(type) {
    const colors = {
        'lecture': 'green',
        'lab': 'purple',
        'tutorial': 'orange',
        'exam': 'red',
        'assignment': 'blue'
    };
    return colors[type] || 'gray';
}

function getClassTypeLabel(type) {
    const labels = {
        'lecture': 'Lecture',
        'lab': 'Lab Session',
        'tutorial': 'Tutorial',
        'exam': 'Exam/Test',
        'assignment': 'Assignment Review'
    };
    return labels[type] || type;
}

function getRecurringLabel(type) {
    const labels = {
        'daily': 'Daily',
        'weekly': 'Weekly',
        'monthly': 'Monthly'
    };
    return labels[type] || type;
}

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
    // Create a simple notification
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
