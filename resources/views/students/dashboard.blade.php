@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ $studentName }}!</h1>
        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
            @if($studentClass)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Class {{ $studentClass }}
                </span>
            @endif
            @if($rollNumber)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Roll No: {{ $rollNumber }}
                </span>
            @endif
            @if($admissionDate)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Admitted: {{ \Carbon\Carbon::parse($admissionDate)->format('M d, Y') }}
                </span>
            @endif
        </div>
        <p class="text-gray-600 mt-2">Here's what's happening with your studies today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Courses -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-500">This Semester</span>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_courses'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Active Courses</div>
        </div>

        <!-- Completed Assignments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm text-green-600 font-medium">+12% from last month</span>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['completed_assignments'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Completed</div>
        </div>

        <!-- Pending Assignments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm text-yellow-600 font-medium">Due soon</span>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['pending_assignments'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Pending</div>
        </div>

        <!-- Attendance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-sm text-green-600 font-medium">Good</span>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['attendance_percentage'] }}%</div>
            <div class="text-sm text-gray-600 mt-1">Attendance Rate</div>
        </div>
    </div>

    <!-- Fee Status Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Fee Status</h2>
            <a href="{{ route('student.fees') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pending Amount -->
            @php
            $pendingBgClass = $pendingAmount > 0 ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200';
            $pendingTextClass = $pendingAmount > 0 ? 'text-yellow-600' : 'text-green-600';
            $pendingIconClass = $pendingAmount > 0 ? 'text-yellow-600' : 'text-green-600';
            @endphp
            <div class="{{ $pendingBgClass }} rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm {{ $pendingTextClass }} mb-1">Pending Amount</p>
                        <p class="text-xl font-bold text-gray-900">₹{{ number_format($pendingAmount, 2) }}</p>
                    </div>
                    <svg class="w-8 h-8 {{ $pendingIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Next Due Date -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 mb-1">Next Due Date</p>
                        <p class="text-xl font-bold text-gray-900">
                            @if($nextDueDate)
                                {{ $nextDueDate->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Payment Status -->
            @php
            $statusBgClass = $paymentStatus == 'paid' ? 'bg-green-50 border border-green-200' : ($paymentStatus == 'overdue' ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200');
            $statusTextClass = $paymentStatus == 'paid' ? 'text-green-600' : ($paymentStatus == 'overdue' ? 'text-red-600' : 'text-yellow-600');
            $statusIconClass = $paymentStatus == 'paid' ? 'text-green-600' : ($paymentStatus == 'overdue' ? 'text-red-600' : 'text-yellow-600');
            @endphp
            <div class="{{ $statusBgClass }} rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm {{ $statusTextClass }} mb-1">Payment Status</p>
                        <p class="text-xl font-bold text-gray-900">
                            @if($paymentStatus == 'paid')
                                Paid
                            @elseif($paymentStatus == 'overdue')
                                Overdue
                            @else
                                Pending
                            @endif
                        </p>
                    </div>
                    <svg class="w-8 h-8 {{ $statusIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($paymentStatus == 'paid')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 7 0 017 0z"/>
                        @elseif($paymentStatus == 'overdue')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @endif
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Fee Alert Message -->
        @php
        $alertBgClass = $isOverdue ? 'bg-red-50 border border-red-200' : ($isDueSoon ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200');
        $alertIconClass = $isOverdue ? 'text-red-600' : ($isDueSoon ? 'text-yellow-600' : 'text-green-600');
        $alertTextClass = $isOverdue ? 'text-red-800' : ($isDueSoon ? 'text-yellow-800' : 'text-green-800');
        @endphp
        <div class="mt-4 p-3 {{ $alertBgClass }} rounded-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 {{ $alertIconClass }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($isOverdue)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @elseif($isDueSoon)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 7 0 017 0z"/>
                    @endif
                </svg>
                <p class="text-sm {{ $alertTextClass }}">
                    @if($isOverdue)
                        Payment is {{ $overdueDays }} days overdue! Please make the payment as soon as possible.
                    @elseif($isDueSoon)
                        Payment is due within 3 days. Please ensure timely payment.
                    @else
                        @if($pendingAmount > 0)
                            Pending amount: ₹{{ number_format($pendingAmount, 2) }}. Please make the payment before the due date.
                        @else
                            All fees are paid up to date. No pending payments.
                        @endif
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Submitted assignment: <span class="font-medium">Mathematics Chapter 5</span></p>
                        <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Attended class: <span class="font-medium">Physics Lecture</span></p>
                        <p class="text-xs text-gray-500 mt-1">Yesterday</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">New assignment posted: <span class="font-medium">Chemistry Lab Report</span></p>
                        <p class="text-xs text-gray-500 mt-1">2 days ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Grade received: <span class="font-medium">A+ in English Essay</span></p>
                        <p class="text-xs text-gray-500 mt-1">3 days ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming PTM Meetings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Upcoming PTM Meetings</h2>
            <div id="ptmDashboardList" class="space-y-4">
                <!-- PTM meetings will be loaded here via JavaScript -->
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">Loading PTM meetings...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('student.assignments') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-all">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div class="text-sm font-medium">View Assignments</div>
            </a>
            <a href="{{ route('student.schedule') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-all">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div class="text-sm font-medium">View Schedule</div>
            </a>
            <a href="{{ route('student.grades') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-all">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <div class="text-sm font-medium">Check Grades</div>
            </a>
            <a href="{{ route('student.profile.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-all">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <div class="text-sm font-medium">Update Profile</div>
            </a>
        </div>
    </div>
</div>

<script>
// Load PTM meetings for dashboard
async function loadDashboardPTM() {
    try {
        const response = await fetch('/student/api/notifications/ptm');
        const data = await response.json();
        
        const ptmList = document.getElementById('ptmDashboardList');
        
        if (data.notifications && data.notifications.length > 0) {
            let ptmHTML = '';
            data.notifications.slice(0, 3).forEach(ptm => {
                const ptmDate = new Date(ptm.meeting_date);
                const formattedDate = ptmDate.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                const formattedTime = ptm.start_time ? new Date('1970-01-01T' + ptm.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : '';
                
                ptmHTML += `
                    <div class="flex items-center gap-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-center">
                            <div class="text-lg font-bold text-blue-600">${ptmDate.getDate()}</div>
                            <div class="text-xs text-blue-600">${ptmDate.toLocaleDateString('en-US', { month: 'short' }).toUpperCase()}</div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">PTM Meeting</p>
                            <p class="text-xs text-gray-500">Class ${ptm.class_name}${ptm.course_type ? ' - ' + ptm.course_type : ''} • ${formattedTime}</p>
                            ${ptm.description ? `<p class="text-xs text-gray-600 mt-1">${ptm.description}</p>` : ''}
                        </div>
                    </div>
                `;
            });
            
            ptmList.innerHTML = ptmHTML;
        } else {
            ptmList.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">No upcoming PTM meetings</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading PTM meetings:', error);
        document.getElementById('ptmDashboardList').innerHTML = `
            <div class="text-center py-8 text-red-500">
                <p class="text-sm">Error loading PTM meetings</p>
            </div>
        `;
    }
}

// Load PTM meetings when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardPTM();
});
</script>
@endsection
