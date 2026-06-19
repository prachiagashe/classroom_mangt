@extends('layouts.app')

@section('title', 'Student Dashboard | StudyFlow Classes')

@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    
    <!-- Custom Glassmorphic Premium Styles -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glow-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glow-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(59, 130, 246, 0.15);
        }
        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
        .bg-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .badge-pulse {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }
    </style>

    <!-- Header / Welcome Section -->
    <div class="hero-gradient rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-xl border border-purple-500/30">
        <!-- Visual Accent Circles -->
        <div class="absolute right-0 top-0 w-80 h-80 bg-blue-600/10 rounded-full filter blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute left-1/3 bottom-0 w-60 h-60 bg-indigo-500/10 rounded-full filter blur-3xl -ml-20 -mb-20"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white tracking-wide uppercase shadow-sm">
                        StudyFlow Classes
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-glass text-blue-200 border border-white/10">
                        {{ $courseType }}
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                   <span class="bg-gradient-to-r from-blue-200 to-indigo-100 bg-clip-text text-transparent">{{ $studentName }}</span>!
                </h1>
                <!-- <p class="text-blue-200/90 text-sm sm:text-base max-w-xl">
                    Unlock your potential today. Here is a live summary of your academic progress, classes, and pending tasks.
                </p> -->
                
                <div class="flex flex-wrap gap-3 pt-2 text-xs text-blue-100">
                    @if($studentClass)
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-glass rounded-xl border border-white/5">
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Class: <strong class="text-white">{{ $studentClass }}</strong></span>
                        </div>
                    @endif
                    @if($rollNumber)
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-glass rounded-xl border border-white/5">
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            <span>Roll No: <strong class="text-white">{{ $rollNumber }}</strong></span>
                        </div>
                    @endif
                    @if($admissionDate)
                        <div class="flex items-center gap-1.5 px-3 py-1.5 bg-glass rounded-xl border border-white/5">
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Admitted: <strong class="text-white">{{ \Carbon\Carbon::parse($admissionDate)->format('M d, Y') }}</strong></span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Header Quick Stats Row -->
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <!-- Attendance Circle Panel -->
                <div class="bg-glass border border-white/10 rounded-2xl p-4 flex items-center gap-4 min-w-[220px]">
                    <div class="relative w-16 h-16 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="32" cy="32" r="28" stroke="rgba(255, 255, 255, 0.1)" stroke-width="5" fill="transparent"/>
                            <circle cx="32" cy="32" r="28" stroke="#10b981" stroke-width="5" fill="transparent"
                                    stroke-dasharray="175.9"
                                    stroke-dashoffset="{{ 175.9 - (175.9 * $attendancePercentage) / 100 }}"/>
                        </svg>
                        <span class="absolute text-sm font-bold text-white">{{ $attendancePercentage }}%</span>
                    </div>
                    <div>
                        <div class="text-xs text-blue-200">Attendance Rate</div>
                        <div class="text-sm font-bold text-white mt-0.5">Rank #{{ $attendanceRank }} in Class</div>
                        <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            {{ $progressStatus }}
                        </span>
                    </div>
                </div>

                <!-- Fee Quick Status Card -->
                <div class="bg-glass border border-white/10 rounded-2xl p-4 flex items-center gap-4 min-w-[220px]">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-blue-200">Pending Fees</div>
                        <div class="text-lg font-bold text-white">₹{{ number_format($pendingAmount, 2) }}</div>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-500/20 text-amber-300 border border-amber-500/30 mt-1">
                            {{ $paymentStatus === 'paid' ? 'Paid' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Quick Actions
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- View Attendance -->
            <a href="{{ route('student.attendance.index') }}" class="glow-card bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-700">View Attendance</span>
            </a>

            <!-- Submit Assignment -->
            <a href="{{ route('student.assignments') }}" class="glow-card bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-700">Submit Assignment</span>
            </a>

            <!-- Download Receipt -->
            @if($latestPaymentId)
                <a href="{{ route('enquiry.fees.payment.receipt.download', $latestPaymentId) }}" class="glow-card bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">Download Receipt</span>
                </a>
            @else
                <a href="{{ route('student.fees') }}" class="glow-card bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group" title="No payment records found yet.">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center mb-3 group-hover:bg-gray-400 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-400">Download Receipt</span>
                </a>
            @endif

            <!-- View Timetable -->
            <a href="{{ route('student.timetable') }}" class="glow-card bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-700">View Timetable</span>
            </a>

            <!-- Contact Teacher Modal Trigger -->
            <button onclick="toggleModal('teacherModal')" class="glow-card w-full bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group focus:outline-none">
                <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center mb-3 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-700">Contact Teacher</span>
            </button>

            <!-- View Study Material Modal Trigger -->
            <button onclick="toggleModal('materialModal')" class="glow-card w-full bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center text-center group focus:outline-none">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-700">Study Material</span>
            </button>
        </div>
    </div>

    <!-- Today's Timetable Section -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Today's Timetable Lectures
                </h2>
                <p class="text-xs text-gray-500 mt-1">Live lecture schedules for today ({{ Carbon\Carbon::now()->format('l, F d, Y') }})</p>
            </div>
            <a href="{{ route('student.timetable') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                Full Timetable
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($todayTimetable->isNotEmpty())
            <!-- Desktop Timetable (Visible on desktop/tablet, hidden on mobile) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lecture Time</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject Name</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teacher Name</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($todayTimetable as $lecture)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                    Period #{{ $lecture->period_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    @if($lecture->start_time && $lecture->end_time)
                                        {{ Carbon\Carbon::parse('1970-01-01 ' . $lecture->start_time)->format('H:i') }} - 
                                        {{ Carbon\Carbon::parse('1970-01-01 ' . $lecture->end_time)->format('H:i') }}
                                    @else
                                        Time Not Assigned
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $lecture->subject->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    {{ $lecture->subject->teacher_name ?? 'N/A' }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Timeline (Visible on mobile/tablet, hidden on desktop) -->
            <div class="space-y-4 md:hidden">
                @foreach($todayTimetable as $lecture)
                    <div class="p-4 rounded-2xl border {{ $lecture->lecture_status === 'Ongoing' ? 'bg-emerald-50/30 border-emerald-200 shadow-sm' : 'bg-gray-50/50 border-gray-150' }} space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-bold text-gray-500">Period #{{ $lecture->period_number }}</span>
                                <h3 class="text-sm font-bold text-gray-900 mt-0.5">{{ $lecture->subject->name ?? 'N/A' }}</h3>
                            </div>

                        </div>
                        <div class="grid grid-cols-2 gap-3 text-xs pt-3 border-t border-gray-100">
                            <div>
                                <span class="text-gray-400 block font-medium">Instructor</span>
                                <span class="font-semibold text-gray-700 mt-0.5 block">{{ $lecture->subject->teacher_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block font-medium">Timing</span>
                                <span class="font-medium text-gray-600 mt-0.5 block">
                                    @if($lecture->start_time && $lecture->end_time)
                                        {{ Carbon\Carbon::parse('1970-01-01 ' . $lecture->start_time)->format('H:i') }} - 
                                        {{ Carbon\Carbon::parse('1970-01-01 ' . $lecture->end_time)->format('H:i') }}
                                    @else
                                        Time Not Assigned
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Beautiful No Lectures State -->
            <div class="text-center py-10 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-900">No lectures scheduled today</h3>
                <p class="text-xs text-gray-500 mt-1">Enjoy your study break or use this time for self-study and assignments!</p>
            </div>
        @endif
    </div>

    <!-- Middle Grid: Assignments & Timeline Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Assignments Card -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Pending Assignments
                    </h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        {{ $pendingAssignments->count() }} Active
                    </span>
                </div>

                @if($pendingAssignments->isNotEmpty())
                    <div class="space-y-4 mt-4 max-h-[360px] overflow-y-auto pr-1">
                        @foreach($pendingAssignments as $assignment)
                            @php
                            $isOverdue = $assignment->status === 'Overdue';
                            $dueDateTime = Carbon\Carbon::parse($assignment->due_date);
                            @endphp
                            <div class="p-4 rounded-2xl border {{ $isOverdue ? 'bg-red-50/40 border-red-100' : 'bg-gray-50/50 border-gray-150' }} hover:shadow-md transition-all flex justify-between items-center gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900">{{ $assignment->title }}</span>
                                        @if($isOverdue)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Assigned by: <strong>{{ $assignment->teacher->name ?? 'Class Instructor' }}</strong>
                                    </p>
                                    <p class="text-xs font-medium {{ $isOverdue ? 'text-red-600' : 'text-gray-600' }}">
                                        Due Date: {{ $dueDateTime->format('M d, Y') }} ({{ $dueDateTime->diffForHumans() }})
                                    </p>
                                </div>
                                <a href="{{ route('student.assignments') }}" class="inline-flex items-center justify-center p-2 rounded-xl bg-white hover:bg-indigo-600 hover:text-white border border-gray-200 text-gray-600 shadow-sm transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 mt-4 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <svg class="w-12 h-12 mx-auto text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-gray-900">All assignments completed!</h3>
                        <p class="text-xs text-gray-500 mt-1">Excellent job! You are up to date on all classroom tasks.</p>
                    </div>
                @endif
            </div>
            
            <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="{{ route('student.assignments') }}" class="w-full inline-flex justify-center items-center py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                    Upload & Submit Assignment
                </a>
            </div>
        </div>

        <!-- Combined Notifications Card -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Timeline & Notifications
                </h2>
            </div>

            @if($combinedNotifications->isNotEmpty())
                <div class="flow-root mt-4 max-h-[380px] overflow-y-auto pr-1">
                    <ul class="-mb-8">
                        @foreach($combinedNotifications as $index => $item)
                            <li>
                                <div class="relative pb-8">
                                    @if($index !== count($combinedNotifications) - 1)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($item['type'] === 'holiday')
                                                <span class="h-8 w-8 rounded-full bg-red-100 border border-red-200 flex items-center justify-center text-red-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </span>
                                            @elseif($item['type'] === 'ptm')
                                                <span class="h-8 w-8 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="h-8 w-8 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5">
                                            <div class="text-xs font-semibold text-gray-800 flex justify-between gap-4">
                                                <span>{{ $item['title'] }}</span>
                                                <span class="text-gray-400 font-normal">{{ $item['date']->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item['message'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-16 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293H8.414a1 1 0 01-.707-.293L6.293 14.707a1 1 0 00-.707-.293H2"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-900">No new notifications</h3>
                    <p class="text-xs text-gray-500 mt-1">Timeline is quiet. Check back later for institute announcements!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Grid: Doubt Sessions & Performance Scoreboard -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Upcoming Doubt Sessions Card -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                    Upcoming Doubt Sessions
                </h2>
                <a href="{{ route('student.doubt-sessions') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    Doubt Center
                </a>
            </div>

            @if($upcomingDoubtSessions->isNotEmpty())
                <div class="space-y-4 mt-4">
                    @foreach($upcomingDoubtSessions as $session)
                        <div class="p-4 rounded-2xl border border-indigo-100 bg-indigo-50/30 flex justify-between items-center gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        {{ $session->subject->name ?? 'General Doubt' }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">Class: {{ $session->class_name }}th</span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Interactive Doubt Session</h3>
                                <p class="text-xs text-gray-500">Instructor: <strong>{{ $session->teacher->name ?? 'Instructor' }}</strong></p>
                                <p class="text-xs text-indigo-700 font-medium">
                                    Scheduled: {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }} | 
                                    {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                </p>
                            </div>
                            <a href="{{ route('student.doubt-sessions') }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition-all">
                                Join
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <svg class="w-12 h-12 mx-auto text-indigo-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.064 14.157l-1.405-1.405A2.032 2.032 0 0115 11.316V8a6.002 6.002 0 00-4-5.659V2a2 2 0 10-4 0v.341C4.67 3.165 3 5.388 3 8v3.316c0 .538-.214 1.055-.595 1.436L1 14.157h5m6 0v1a3 3 0 11-6 0v-1m6 0H3"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-900">No doubt sessions scheduled</h3>
                    <p class="text-xs text-gray-500 mt-1">If you have specific doubts, raise a ticket or message your faculty!</p>
                </div>
            @endif
        </div>

        <!-- Academic Performance Scoreboard Card -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Student Performance Scoreboard
                </h2>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    Active Term
                </span>
            </div>

            <!-- Scoreboard Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                <!-- Academic Score Card -->
                <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-150 text-center flex flex-col justify-between">
                    <div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Academic Index</div>
                        <div class="text-3xl font-extrabold text-blue-600 mt-2">88.5%</div>
                    </div>
                    <span class="text-[10px] font-medium text-emerald-600 mt-3 flex items-center justify-center gap-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Assignments evaluated
                    </span>
                </div>

                <!-- Class Attendance Rank Card -->
                <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-150 text-center flex flex-col justify-between">
                    <div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Attendance Rank</div>
                        <div class="text-3xl font-extrabold text-indigo-600 mt-2">#{{ $attendanceRank }}</div>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 mt-3 block">
                        Top percentile tier
                    </span>
                </div>

                <!-- Progress status card -->
                <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-150 text-center flex flex-col justify-between">
                    <div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Progress Status</div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-700 border border-emerald-500/30 mt-3">
                            {{ $progressStatus }}
                        </span>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 mt-3 block">
                        Term Status Checked
                    </span>
                </div>
            </div>
            
            <div class="p-4 bg-yellow-50/50 border border-yellow-100 rounded-2xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-xs font-bold text-yellow-800">Institute Scoreboard Insight</h4>
                        <p class="text-[11px] text-yellow-700 mt-0.5">
                            Keep up the amazing consistency! Highly active attendance rates and timely assignment uploads directly impact mock exams prep. Focus on Rotational Dynamics doubt session next!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- 1. Contact Teacher Modal -->
<div id="teacherModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-filter backdrop-blur-sm transition-opacity" onclick="toggleModal('teacherModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Content Panel -->
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all my-8 mx-4 sm:max-w-lg sm:w-full border border-gray-100">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-lg font-bold">Class Instructors Directory</h3>
                <button onclick="toggleModal('teacherModal')" class="text-white hover:text-indigo-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="px-6 py-6 space-y-4 max-h-[400px] overflow-y-auto">
                @if($classTeachers->isNotEmpty())
                    @foreach($classTeachers as $teacher)
                        <div class="p-4 rounded-2xl border border-gray-150 bg-gray-50/50 flex justify-between items-center gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $teacher['name'] }}</h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 mt-1">
                                    {{ $teacher['subject'] }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1.5">{{ $teacher['email'] }}</p>
                            </div>
                            <a href="mailto:{{ $teacher['email'] }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition-colors">
                                Email
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm">No teacher details assigned to this class yet.</p>
                    </div>
                @endif
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button onclick="toggleModal('teacherModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold rounded-xl transition-colors focus:outline-none">
                    Close Directory
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Study Material Modal -->
<div id="materialModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-filter backdrop-blur-sm transition-opacity" onclick="toggleModal('materialModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Content Panel -->
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all my-8 mx-4 sm:max-w-lg sm:w-full border border-gray-100">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-lg font-bold">Preparation & Study Library</h3>
                <button onclick="toggleModal('materialModal')" class="text-white hover:text-indigo-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="px-6 py-6 space-y-4 max-h-[400px] overflow-y-auto">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Download core formulas, quick study guides, and mock prep handbooks curated by the StudyFlow Classes department.
                </p>
                
                <!-- Mock Materials List -->
                <div class="space-y-3 pt-2">
                    <div class="p-4 rounded-2xl border border-gray-150 bg-gray-50/50 flex justify-between items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">JEE Physics Formula Sheet</h4>
                            <p class="text-xs text-gray-500 mt-1">Mechanics, Rotation & Thermodynamics (PDF • 4.8MB)</p>
                        </div>
                        <a href="#" onclick="alert('Download started successfully!')" class="inline-flex items-center p-2 rounded-xl bg-white hover:bg-indigo-600 hover:text-white border border-gray-200 text-gray-600 shadow-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>

                    <div class="p-4 rounded-2xl border border-gray-150 bg-gray-50/50 flex justify-between items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">NEET Biology Summary Booklet</h4>
                            <p class="text-xs text-gray-500 mt-1">Human Physiology & Genetics Recap (PDF • 6.2MB)</p>
                        </div>
                        <a href="#" onclick="alert('Download started successfully!')" class="inline-flex items-center p-2 rounded-xl bg-white hover:bg-indigo-600 hover:text-white border border-gray-200 text-gray-600 shadow-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>

                    <div class="p-4 rounded-2xl border border-gray-150 bg-gray-50/50 flex justify-between items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Bansal Prep Mathematics Workbook</h4>
                            <p class="text-xs text-gray-500 mt-1">Calculus & Vector algebra advanced exercises (PDF • 8.1MB)</p>
                        </div>
                        <a href="#" onclick="alert('Download started successfully!')" class="inline-flex items-center p-2 rounded-xl bg-white hover:bg-indigo-600 hover:text-white border border-gray-200 text-gray-600 shadow-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>

                    <div class="p-4 rounded-2xl border border-gray-150 bg-gray-50/50 flex justify-between items-center gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Chemistry Rapid Revision Sheet</h4>
                            <p class="text-xs text-gray-500 mt-1">Organic Chemistry mechanisms & shortcuts (PDF • 3.5MB)</p>
                        </div>
                        <a href="#" onclick="alert('Download started successfully!')" class="inline-flex items-center p-2 rounded-xl bg-white hover:bg-indigo-600 hover:text-white border border-gray-200 text-gray-600 shadow-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button onclick="toggleModal('materialModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold rounded-xl transition-colors focus:outline-none">
                    Close Library
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Modal Toggle Utility
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Lock background scroll
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Unlock scroll
        }
    }
</script>
@endsection
