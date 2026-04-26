@extends('layouts.app')

@section('title', 'Student Progress')

@section('page-title', 'Student Progress')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Student Progress</h1>
                <div class="flex items-center gap-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-lg font-semibold text-gray-700">{{ $student->student_name }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="text-lg font-medium text-blue-600">{{ $student->class }}</span>
                    </div>
                </div>
            </div>
            <!-- Back Button -->
            <a href="{{ request()->header('referer') ?? route('enquiry.admissions.index') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Admission
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Present Days Card -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium mb-1">Total Present Days</p>
                    <p class="text-3xl font-bold text-green-700">{{ $presentDays }}</p>
                    <p class="text-green-500 text-xs mt-1">This Month</p>
                </div>
                <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Absent Days Card -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium mb-1">Total Absent Days</p>
                    <p class="text-3xl font-bold text-red-700">{{ $absentDays }}</p>
                    <p class="text-red-500 text-xs mt-1">This Month</p>
                </div>
                <div class="w-16 h-16 bg-red-200 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Attendance Percentage Card -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium mb-1">Attendance Percentage</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $attendancePercentage }}%</p>
                    <p class="text-blue-500 text-xs mt-1">{{ $attendancePercentage >= 90 ? 'Excellent' : ($attendancePercentage >= 75 ? 'Good' : 'Needs Improvement') }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Attendance Marking Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Mark Attendance for {{ $student->student_name }}</h2>
        
        @php
            $currentMonth = \Carbon\Carbon::now()->format('F Y');
            $currentDay = \Carbon\Carbon::now()->day;
            $attendance = \App\Models\StudentAttendence::where('roll_no', $student->roll_number)
                ->where('month', $currentMonth)
                ->first();
                
            $todayColumn = "day_{$currentDay}";
            $todayStatus = isset($attendance) ? $attendance->$todayColumn : null;
        @endphp
        
        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-2">Mark attendance for today ({{ \Carbon\Carbon::now()->format('d F Y') }}):</p>
            
            @if(!$todayStatus)
                <form id="admin-attendance-form" class="flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="roll_no" value="{{ $student->roll_number }}">
                    <input type="hidden" name="name" value="{{ $student->student_name }}">
                    <input type="hidden" name="month" value="{{ $currentMonth }}">
                    <input type="hidden" name="day" value="{{ $currentDay }}">
                    
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="markAttendance('P')" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
                            </svg>
                            Mark Present
                        </button>
                        
                        <button type="button" onclick="markAttendance('A')" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Mark Absent
                        </button>
                    </div>
                </form>
            @else
                <div class="flex items-center gap-3">
                    @if($todayStatus === 'P')
                        <div class="flex items-center gap-2 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
                            </svg>
                            <span class="font-medium">Already marked as Present</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="font-medium">Already marked as Absent</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        <!-- Quick Stats -->
        @if($attendance)
        <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $attendance->total_p }}</p>
                <p class="text-sm text-gray-600">Present Days</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">{{ $attendance->total_a }}</p>
                <p class="text-sm text-gray-600">Absent Days</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $attendance->percentage }}%</p>
                <p class="text-sm text-gray-600">Attendance %</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Attendance Calendar</h2>
            <div class="flex items-center gap-4">
                <!-- View Toggle -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button onclick="showAttendanceView('calendar')" id="calendar-view-btn" class="px-3 py-1 rounded-md text-sm font-medium bg-white text-gray-900 shadow-sm">
                        Calendar View
                    </button>
                    <button onclick="showAttendanceView('table')" id="table-view-btn" class="px-3 py-1 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700">
                        Table View
                    </button>
                </div>
                
                <!-- Legend -->
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-sm text-gray-600">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-500 rounded"></div>
                    <span class="text-sm text-gray-600">Absent</span>
                </div>
            </div>
        </div>
        
        <!-- Calendar View -->
        <div id="calendar-view">
            <!-- Current Month Calendar -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Month Calendar</h3>
                <div id="current-month-calendar" class="min-h-[400px]"></div>
            </div>
            
            <!-- Full Calendar -->
            <div id="attendance-calendar" class="min-h-[600px]"></div>
        </div>
        
        <!-- Table View -->
        <div id="table-view" class="hidden">
            <!-- Current Month Detailed View -->
            <div class="mb-6">
                @php
                    $allAttendances = \App\Models\StudentAttendence::where('roll_no', $student->roll_number)
                        ->orderBy('month', 'desc')
                        ->get();
                @endphp
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Attendance Records</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roll No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                @for($day = 1; $day <= 31; $day++)
                                    @php
                                        $dayDate = \Carbon\Carbon::parse($day . " March 2026");
                                        $isSunday = $dayDate->dayOfWeek === \Carbon\Carbon::SUNDAY;
                                        $bgColor = $isSunday ? 'bg-amber-100' : 'bg-gray-50';
                                    @endphp
                                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[40px] {{ $bgColor }}">{{ $day }}</th>
                                @endfor
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total P</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total A</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($allAttendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $attendance->roll_no }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $attendance->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $attendance->month }}</td>
                                
                                @for($day = 1; $day <= 31; $day++)
                                    @php
                                        $dayColumn = "day_{$day}";
                                        $status = $attendance->$dayColumn;
                                        $dayDate = \Carbon\Carbon::parse($day . " " . $attendance->month . " 2026");
                                        $isSunday = $dayDate->dayOfWeek === \Carbon\Carbon::SUNDAY;
                                        
                                        // Check for holidays
                                        $holidays = [
                                            '2026-01-26' => 'Republic Day',
                                            '2026-08-15' => 'Independence Day',
                                            '2026-10-02' => 'Gandhi Jayanti',
                                            '2026-12-25' => 'Christmas',
                                        ];
                                        $dateStr = $dayDate->format('Y-m-d');
                                        $isHoliday = isset($holidays[$dateStr]);
                                        
                                        if ($isSunday) {
                                            $bgColor = 'bg-amber-100';
                                            $textColor = 'text-amber-800';
                                            $content = 'S';
                                        } elseif ($isHoliday) {
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';
                                            $content = 'H';
                                        } elseif ($status === 'P') {
                                            $bgColor = 'bg-green-100';
                                            $textColor = 'text-green-800';
                                            $content = 'P';
                                        } elseif ($status === 'A') {
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';
                                            $content = 'A';
                                        } else {
                                            $bgColor = 'bg-gray-100';
                                            $textColor = 'text-gray-500';
                                            $content = '-';
                                        }
                                    @endphp
                                    <td class="px-2 py-3 text-center text-sm {{ $bgColor }} {{ $textColor }}">
                                        {{ $content }}
                                    </td>
                                @endfor
                                
                                <td class="px-4 py-3 text-center text-sm font-medium text-green-600">{{ $attendance->total_p }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-red-600">{{ $attendance->total_a }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-blue-600">{{ $attendance->percentage }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Legend -->
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-100 rounded"></div>
                        <span class="text-gray-600">P = Present</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-100 rounded"></div>
                        <span class="text-gray-600">A = Absent</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-amber-100 rounded"></div>
                        <span class="text-gray-600">S = Sunday</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-100 rounded"></div>
                        <span class="text-gray-600">H = Holiday</span>
                    </div>
                    <!-- <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-100 rounded"></div>
                        <span class="text-gray-600">- = No Data</span>
                    </div> -->
                </div>
            </div>
            
            <!-- Monthly Summary View -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Summary</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Present Days</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absent Days</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $allAttendances = \App\Models\StudentAttendence::where('roll_no', $student->roll_number)
                                    ->orderBy('month', 'desc')
                                    ->take(6) // Last 6 months
                                    ->get();
                            @endphp
                            @foreach($allAttendances as $att)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $att->month }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-green-600">{{ $att->total_p }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-red-600">{{ $att->total_a }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-blue-600">{{ $att->percentage }}%</td>
                                <td class="px-4 py-3 text-center">
                                    @if($att->percentage >= 90)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Excellent</span>
                                    @elseif($att->percentage >= 75)
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Good</span>
                                    @elseif($att->percentage >= 60)
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Average</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Poor</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Monthly Trend -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend</h3>
            <div class="space-y-3">
                @foreach($monthlyTrend as $trend)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $trend['month'] }}</span>
                        <span class="text-green-600 font-medium">{{ $trend['percentage'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Subject-wise Attendance -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject-wise Attendance</h3>
            <div class="space-y-3">
                @foreach($subjectAttendance as $subject)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $subject['subject'] }}</span>
                        <span class="text-green-600 font-medium">{{ $subject['percentage'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('attendance-calendar');
    
    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        contentHeight: 600,
        aspectRatio: 1.8,
        
        // Dynamic attendance events from database
        events: @json($attendanceEvents),
        
        // Customize calendar appearance
        eventDidMount: function(info) {
            // Remove event titles from display (show only colors)
            if (info.event.display === 'background') {
                info.el.style.cursor = 'default';
            }
        },
        
        // Add hover effects
        eventMouseEnter: function(info) {
            if (info.event.display === 'background') {
                info.el.style.opacity = '0.8';
            }
        },
        
        eventMouseLeave: function(info) {
            if (info.event.display === 'background') {
                info.el.style.opacity = '1';
            }
        },
        
        // Responsive settings
        windowResize: function(arg) {
            if (window.innerWidth < 768) {
                calendar.setOption('height', 400);
            } else {
                calendar.setOption('height', 600);
            }
        }
    });
    
    calendar.render();
});

// Auto-refresh calendar every 10 seconds to show latest attendance updates
setInterval(function() {
    // Only refresh if there are recent attendance updates
    fetch('{{ route("enquiry.admissions.track-attendence", $student->id) }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Check if content has changed
        const currentContent = document.documentElement.innerHTML;
        if (html !== currentContent) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.log('Error checking for updates:', error);
    });
}, 10000);

// Function to toggle between calendar and table views
function showAttendanceView(view) {
    const calendarView = document.getElementById('calendar-view');
    const tableView = document.getElementById('table-view');
    const calendarBtn = document.getElementById('calendar-view-btn');
    const tableBtn = document.getElementById('table-view-btn');
    
    if (view === 'calendar') {
        calendarView.classList.remove('hidden');
        tableView.classList.add('hidden');
        calendarBtn.className = 'px-3 py-1 rounded-md text-sm font-medium bg-white text-gray-900 shadow-sm';
        tableBtn.className = 'px-3 py-1 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700';
    } else {
        calendarView.classList.add('hidden');
        tableView.classList.remove('hidden');
        calendarBtn.className = 'px-3 py-1 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700';
        tableBtn.className = 'px-3 py-1 rounded-md text-sm font-medium bg-white text-gray-900 shadow-sm';
    }
}

// Make function globally available
window.showAttendanceView = showAttendanceView;

// Admin attendance marking function
function markAttendance(status) {
    const form = document.getElementById('admin-attendance-form');
    const formData = new FormData(form);
    formData.append('status', status);
    
    // Disable buttons during submission
    const buttons = form.querySelectorAll('button');
    buttons.forEach(btn => btn.disabled = true);
    
    fetch('{{ route("admin.attendance.mark") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            roll_no: formData.get('roll_no'),
            name: formData.get('name'),
            month: formData.get('month'),
            day: formData.get('day'),
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            // Reload page after 1.5 seconds to show updated attendance
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showErrorMessage(data.error || 'Failed to mark attendance');
            buttons.forEach(btn => btn.disabled = false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred while marking attendance');
        buttons.forEach(btn => btn.disabled = false);
    });
}

function showSuccessMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-green-100 text-green-700 px-6 py-3 rounded-lg border border-green-200 shadow-lg z-50';
    messageDiv.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

function showErrorMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-red-100 text-red-700 px-6 py-3 rounded-lg border border-red-200 shadow-lg z-50';
    messageDiv.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Make functions globally available
window.markAttendance = markAttendance;
window.showSuccessMessage = showSuccessMessage;
window.showErrorMessage = showErrorMessage;
</script>
@endsection
