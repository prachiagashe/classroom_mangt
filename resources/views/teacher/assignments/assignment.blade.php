@extends('layouts.app')

@section('title', 'My Schedule & Assignments')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Schedule & Assignments</h1>
        <div class="flex gap-3">
            <button onclick="openAssignmentModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                ➕ Add Assignment
            </button>
            <!-- <button onclick="openScheduleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                ➕ Add Schedule
            </button> -->
            <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:text-blue-800 flex items-center font-medium">
                ← Back
            </a>
        </div>
    </div>

    @php
        $subjectsData = json_decode($employee->assigned_subjects, true);
        $formattedSubjects = [];
        if (json_last_error() === JSON_ERROR_NONE && is_array($subjectsData)) {
            foreach ($subjectsData as $cls => $subs) {
                foreach ($subs as $sub) {
                    $formattedSubjects[] = $sub . ' (' . $cls . ')';
                }
            }
        } else if ($employee->assigned_subjects) {
            $formattedSubjects = explode(', ', $employee->assigned_subjects);
        }

        $classesData = [];
        if ($employee->assigned_classes) {
            $classesData = explode(', ', $employee->assigned_classes);
        }
    @endphp

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Classes</p>
                    <p class="text-2xl font-bold text-blue-900">{{ count($classesData) }}</p>
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
                    <p class="text-2xl font-bold text-green-900">{{ count($formattedSubjects) }}</p>
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
                @if(!empty($classesData))
                    <div class="flex flex-wrap gap-3">
                        @foreach($classesData as $class)
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
                @if(!empty($formattedSubjects))
                    <div class="flex flex-wrap gap-3">
                        @foreach($formattedSubjects as $subject)
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
    <!-- SECTION: Created Assignments & Submissions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">📂 Published Assignments</h2>
            <p class="text-sm text-gray-500 mt-1">Track student submissions and assign marks.</p>
        </div>
        
        <div class="p-6">
            @if($createdAssignments->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-lg font-semibold">No assignments published yet</p>
                    <p class="text-sm text-gray-400 mt-1">Use 'Add Assignment' to begin mapping coursework.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($createdAssignments as $assignment)
                        <div class="border border-gray-150 rounded-xl overflow-hidden shadow-sm">
                            <div class="p-5 bg-gray-50 border-b border-gray-150 flex flex-col md:flex-row justify-between md:items-center gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-wider px-2 py-1 rounded">Class {{ $assignment->class_id }}</span>
                                        <span class="bg-green-100 text-green-800 text-xs font-bold uppercase tracking-wider px-2 py-1 rounded">{{ $assignment->subject }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $assignment->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($assignment->description, 150) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Due Date</p>
                                    <p class="text-sm font-bold text-red-600">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</p>
                                    
                                    <button onclick="toggleSubmissions('{{ $assignment->id }}')" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wider transition-colors">
                                        View Submissions ({{ $assignment->submissions->count() }})
                                        <svg id="arrow-{{ $assignment->id }}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Submissions List -->
                            <div id="submissions-{{ $assignment->id }}" class="hidden border-t border-gray-150 bg-white">
                                @if($assignment->submissions->isEmpty())
                                    <div class="p-6 text-center text-gray-500 text-sm">
                                        No students have submitted this assignment yet.
                                    </div>
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm text-gray-600">
                                            <thead class="bg-gray-100 text-gray-700 text-xs font-bold uppercase tracking-wider">
                                                <tr>
                                                    <th class="px-6 py-3">Student Name</th>
                                                    <th class="px-6 py-3">File</th>
                                                    <th class="px-6 py-3">Submitted At</th>
                                                    <th class="px-6 py-3">Status</th>
                                                    <th class="px-6 py-3">Marks</th>
                                                    <th class="px-6 py-3 text-right">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-150">
                                                @foreach($assignment->submissions as $submission)
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-6 py-4 font-semibold text-gray-900">{{ optional($submission->student)->name ?? 'Student' }}</td>
                                                        <td class="px-6 py-4">
                                                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1 font-semibold">
                                                                📄 View PDF
                                                            </a>
                                                        </td>
                                                        <td class="px-6 py-4 text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, h:i A') }}
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            @if($submission->status === 'checked')
                                                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">Checked</span>
                                                            @else
                                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">Submitted</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 font-bold text-gray-900">
                                                            {{ $submission->marks ?? 'Pending' }}
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <form method="POST" action="{{ route('teacher.assignments.evaluate', $submission->id) }}" class="flex items-center justify-end gap-2">
                                                                @csrf
                                                                <input type="text" name="marks" required placeholder="Marks" class="w-20 text-sm px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $submission->marks }}">
                                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3 py-1.5 rounded-lg uppercase tracking-wider transition-colors shadow-sm">
                                                                    Save
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
            <div class="flex gap-3 justify-end bg-gray-50 p-4 -mx-6 -mb-6 border-t border-gray-200">
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
    console.log('openScheduleModal called with id:', id);
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

function openAssignmentModal() {
    document.getElementById('assignmentModal').classList.remove('hidden');
}

function closeAssignmentModal() {
    document.getElementById('assignmentModal').classList.add('hidden');
}

function toggleSubmissions(id) {
    const el = document.getElementById('submissions-' + id);
    const arrow = document.getElementById('arrow-' + id);
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        arrow.classList.add('rotate-180');
    } else {
        el.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
}

// Auto-open assignment submissions based on query parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const openId = urlParams.get('open');
    if (openId) {
        toggleSubmissions(openId);
        const element = document.getElementById('submissions-' + openId);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    }
});
</script>
@endsection
