@extends('layouts.app')

@section('title', 'Attendance')

@section('page-title', 'Attendance')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Attendance</h1>
        <p class="text-gray-600">View and manage your attendance records.</p>
    </div>

    <!-- View Toggle -->
    <div class="mb-6">
        <div class="flex gap-2">
            <button onclick="document.getElementById('current-view').style.display='block'; document.getElementById('detailed-view').style.display='none'; this.className='bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors hover:bg-blue-700 cursor-pointer shadow-sm'; document.getElementById('detailed-view-btn').className='bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors hover:bg-gray-300 cursor-pointer shadow-sm';" id="current-view-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors hover:bg-blue-700 cursor-pointer shadow-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Current Month
            </button>
            <button onclick="document.getElementById('current-view').style.display='none'; document.getElementById('detailed-view').style.display='block'; this.className='bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors hover:bg-blue-700 cursor-pointer shadow-sm'; document.getElementById('current-view-btn').className='bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors hover:bg-gray-300 cursor-pointer shadow-sm';" id="detailed-view-btn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors hover:bg-gray-300 cursor-pointer shadow-sm">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Detailed History
            </button>
        </div>
    </div>

    <!-- Current Month View -->
    <div id="current-view" class="space-y-6">
        <!-- Mark Attendance Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Mark Today's Attendance</h3>
            <div class="flex items-center gap-4">
                @php
                    $today = \Carbon\Carbon::now()->day;
                    $todayColumn = "day_{$today}";
                    $todayStatus = isset($attendance) ? $attendance->$todayColumn : null;
                @endphp
                
                @if(!$todayStatus)
                    <form id="attendance-form" class="flex items-center gap-4">
                        @csrf
                        <input type="hidden" name="status" value="P">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
                            </svg>
                            Mark Present
                        </button>
                    </form>
                    <p class="text-sm text-gray-600">Click "Mark Present" to mark your attendance for today. Unmarked days will be automatically marked as absent.</p>
                @else
                    <div class="flex items-center gap-3">
                        @if($todayStatus === 'P')
                            <div class="flex items-center gap-2 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
                                </svg>
                                <span class="font-medium">Present</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="font-medium">Absent</span>
                            </div>
                        @endif
                        <p class="text-sm text-gray-600">Today's attendance has been recorded.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Current Month Attendance Summary -->
        @if(isset($attendance))
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Month: {{ $attendance->month }}</h2>
            
            <!-- Compact Summary Table -->
            <div class="overflow-x-auto mb-6">
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
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $attendance->month }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-green-600">{{ $attendance->total_p }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-red-600">{{ $attendance->total_a }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-blue-600">{{ $attendance->percentage }}%</td>
                            <td class="px-4 py-3 text-center">
                                @if($attendance->percentage >= 90)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Excellent</span>
                                @elseif($attendance->percentage >= 75)
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Good</span>
                                @elseif($attendance->percentage >= 60)
                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Average</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Poor</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Current Month Attendance Calendar -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Month Attendance Calendar</h3>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    @php
                        $currentMonth = \Carbon\Carbon::now();
                        $daysInMonth = $currentMonth->daysInMonth;
                        $firstDayOfMonth = $currentMonth->copy()->startOfMonth()->dayOfWeek;
                        $monthYear = $currentMonth->format('F Y');
                    @endphp
                    
                    <!-- Month Header -->
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">{{ $monthYear }}</h4>
                    </div>
                    
                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-1">
                        <!-- Day Headers -->
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Sun</div>
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Mon</div>
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Tue</div>
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Wed</div>
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Thu</div>
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Fri</div>
                        <div class="text-center text-xs font-medium text-gray-600 py-2">Sat</div>
                        
                        <!-- Empty Cells for Days Before Month Starts -->
                        @for($i = 0; $i < $firstDayOfMonth; $i++)
                            <div class="h-12"></div>
                        @endfor
                        
                        <!-- Calendar Days -->
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $dayColumn = "day_{$day}";
                                $status = isset($attendance) ? $attendance->$dayColumn : null;
                                $dayDate = \Carbon\Carbon::create(2026, $currentMonth->month, $day);
                                $isSunday = $dayDate->dayOfWeek === \Carbon\Carbon::SUNDAY;
                                $isToday = $day == $currentMonth->day;
                                
                                // Check for holidays
                                $holidays = [
                                    '2026-01-26' => 'Republic Day',
                                    '2026-08-15' => 'Independence Day',
                                    '2026-10-02' => 'Gandhi Jayanti',
                                    '2026-12-25' => 'Christmas',
                                ];
                                $dateStr = $dayDate->format('Y-m-d');
                                $isHoliday = isset($holidays[$dateStr]);
                                
                                // Determine styling
                                if ($isSunday) {
                                    $bgColor = 'bg-amber-100';
                                    $textColor = 'text-amber-800';
                                    $borderColor = 'border-amber-300';
                                } elseif ($isHoliday) {
                                    $bgColor = 'bg-red-100';
                                    $textColor = 'text-red-800';
                                    $borderColor = 'border-red-300';
                                } elseif ($status === 'P') {
                                    $bgColor = 'bg-green-100';
                                    $textColor = 'text-green-800';
                                    $borderColor = 'border-green-300';
                                } elseif ($status === 'A') {
                                    $bgColor = 'bg-red-100';
                                    $textColor = 'text-red-800';
                                    $borderColor = 'border-red-300';
                                } else {
                                    $bgColor = 'bg-gray-50';
                                    $textColor = 'text-gray-600';
                                    $borderColor = 'border-gray-300';
                                }
                                
                                if ($isToday) {
                                    $borderColor .= ' ring-2 ring-blue-500';
                                }
                            @endphp
                            
                            <div class="h-12 border {{ $borderColor }} {{ $bgColor }} rounded flex flex-col items-center justify-center cursor-pointer hover:opacity-80 transition-opacity">
                                <div class="text-sm font-medium {{ $textColor }}">{{ $day }}</div>
                                @if($status === 'P')
                                    <div class="text-xs font-bold {{ $textColor }}">P</div>
                                @elseif($status === 'A')
                                    <div class="text-xs font-bold {{ $textColor }}">A</div>
                                @elseif($isSunday)
                                    <div class="text-xs font-bold {{ $textColor }}">S</div>
                                @elseif($isHoliday)
                                    <div class="text-xs font-bold {{ $textColor }}">H</div>
                                @else
                                    <div class="text-xs {{ $textColor }}">-</div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    
                    <!-- Calendar Legend -->
                    <div class="mt-4 flex flex-wrap items-center justify-center gap-3 text-xs">
                        <div class="flex items-center gap-1">
                            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                            <span class="text-gray-600">Present (P)</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                            <span class="text-gray-600">Absent (A)</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-4 h-4 bg-amber-100 border border-amber-300 rounded"></div>
                            <span class="text-gray-600">Sunday (S)</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                            <span class="text-gray-600">Holiday (H)</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-4 h-4 bg-gray-50 border border-gray-300 rounded"></div>
                            <span class="text-gray-600">Not Marked</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Cards -->
            <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-600 text-sm font-medium">Present Days</p>
                    <p class="text-2xl font-bold text-green-700">{{ $attendance->total_p }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-600 text-sm font-medium">Absent Days</p>
                    <p class="text-2xl font-bold text-red-700">{{ $attendance->total_a }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-600 text-sm font-medium">Percentage</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $attendance->percentage }}%</p>
                </div>
            </div>
             -->
            <!-- Toggle for Detailed View -->
            <div class="mb-4">
                <button onclick="toggleDetailedView()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors text-sm">
                    <span id="toggle-text">Show Detailed Calendar View</span>
                </button>
            </div>
            
            <!-- Detailed Calendar View (Hidden by default) -->
            <div id="detailed-calendar-view" class="hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Attendance Table</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Roll No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Month</th>
                                @for($day = 1; $day <= 31; $day++)
                                    @php
                                        $dayDate = \Carbon\Carbon::create(2026, \Carbon\Carbon::now()->month, $day);
                                        $isSunday = $dayDate->dayOfWeek === \Carbon\Carbon::SUNDAY;
                                        $bgColor = $isSunday ? 'bg-amber-100' : 'bg-gray-50';
                                    @endphp
                                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[40px] border border-gray-300 {{ $bgColor }}">{{ $day }}</th>
                                @endfor
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Total P</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Total A</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($attendance))
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">{{ $attendance->roll_no }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">{{ $attendance->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">{{ $attendance->month }}</td>
                                
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
                                    <td class="px-2 py-3 text-center text-sm font-medium {{ $bgColor }} {{ $textColor }} border border-gray-300">
                                        {{ $content }}
                                    </td>
                                @endfor
                                
                                <td class="px-4 py-3 text-center text-sm font-medium text-green-600 border border-gray-300">{{ $attendance->total_p }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-red-600 border border-gray-300">{{ $attendance->total_a }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-blue-600 border border-gray-300">{{ $attendance->percentage }}%</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="36" class="px-4 py-8 text-center text-gray-500 border border-gray-300">
                                    No attendance data available for current month
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Legend -->
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-100 rounded border border-gray-300"></div>
                        <span class="text-gray-600">P = Present</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-100 rounded border border-gray-300"></div>
                        <span class="text-gray-600">A = Absent</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-amber-100 rounded border border-gray-300"></div>
                        <span class="text-gray-600">S = Sunday</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-100 rounded border border-gray-300"></div>
                        <span class="text-gray-600">H = Holiday</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-100 rounded border border-gray-300"></div>
                        <span class="text-gray-600">- = Not Marked</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Detailed History View -->
    <div id="detailed-view" style="display: none;">
        <!-- Attendance History -->
        @if(isset($attendances) && $attendances->count() > 0)
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Present Days</p>
                        <p class="text-2xl font-bold text-green-600">{{ $attendances->sum('total_p') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 0 017 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Absent Days</p>
                        <p class="text-2xl font-bold text-red-600">{{ $attendances->sum('total_a') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overall Percentage</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $attendances->sum('total_p') > 0 ? round(($attendances->sum('total_p') / ($attendances->sum('total_p') + $attendances->sum('total_a'))) * 100, 2) : 0 }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Months Recorded</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $attendances->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Summary Table
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Monthly Summary</h2>
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
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $attendance->month }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-green-600">{{ $attendance->total_p }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-red-600">{{ $attendance->total_a }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-blue-600">{{ $attendance->percentage }}%</td>
                            <td class="px-4 py-3 text-center">
                                @if($attendance->percentage >= 90)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Excellent</span>
                                @elseif($attendance->percentage >= 75)
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Good</span>
                                @elseif($attendance->percentage >= 60)
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
        </div> -->

        <!-- Detailed Day-wise Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Detailed Day-wise Attendance</h2>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ $attendances->count() }}</span> months of records
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Roll No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Month</th>
                            @for($day = 1; $day <= 31; $day++)
                                @php
                                    $dayDate = \Carbon\Carbon::create(2026, \Carbon\Carbon::now()->month, $day);
                                    $isSunday = $dayDate->dayOfWeek === \Carbon\Carbon::SUNDAY;
                                    $bgColor = $isSunday ? 'bg-amber-100' : 'bg-gray-50';
                                @endphp
                                <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[40px] border border-gray-300 {{ $bgColor }}">{{ $day }}</th>
                            @endfor
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Total P</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Total A</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">{{ $attendance->roll_no }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">{{ $attendance->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">{{ $attendance->month }}</td>
                            
                            @for($day = 1; $day <= 31; $day++)
                                @php
                                    $dayColumn = "day_{$day}";
                                    $status = $attendance->$dayColumn;
                                    
                                    // Check for holidays
                                    $holidays = [
                                        '2026-01-26' => 'Republic Day',
                                        '2026-08-15' => 'Independence Day',
                                        '2026-10-02' => 'Gandhi Jayanti',
                                        '2026-12-25' => 'Christmas',
                                    ];
                                    
                                    // Parse day date for holiday check
                                    try {
                                        $dayDate = \Carbon\Carbon::parse($day . " " . $attendance->month . " 2026");
                                        $dateStr = $dayDate->format('Y-m-d');
                                        $isHoliday = isset($holidays[$dateStr]);
                                        $isSunday = $dayDate->dayOfWeek === \Carbon\Carbon::SUNDAY;
                                        
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
                                    } catch (\Exception $e) {
                                        $bgColor = 'bg-gray-100';
                                        $textColor = 'text-gray-500';
                                        $content = '-';
                                    }
                                @endphp
                                <td class="px-2 py-3 text-center text-sm font-medium {{ $bgColor }} {{ $textColor }} border border-gray-300">
                                    {{ $content }}
                                </td>
                            @endfor
                            
                            <td class="px-4 py-3 text-center text-sm font-medium text-green-600 border border-gray-300">{{ $attendance->total_p }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-red-600 border border-gray-300">{{ $attendance->total_a }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-blue-600 border border-gray-300">{{ $attendance->percentage }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Table Legend -->
            <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-100 rounded border border-gray-300"></div>
                    <span class="text-gray-600">P = Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-100 rounded border border-gray-300"></div>
                    <span class="text-gray-600">A = Absent</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-amber-100 rounded border border-gray-300"></div>
                    <span class="text-gray-600">S = Sunday</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-100 rounded border border-gray-300"></div>
                    <span class="text-gray-600">H = Holiday</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-100 rounded border border-gray-300"></div>
                    <span class="text-gray-600">- = Not Marked</span>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendance Records</h3>
                <p class="text-gray-500">You haven't marked any attendance yet.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle attendance form submission
    const attendanceForm = document.getElementById('attendance-form');
    if (attendanceForm) {
        attendanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Marking...';
            
            fetch('{{ route("student.attendance.mark") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: formData.get('status')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message briefly
                    showSuccessMessage(data.message);
                    
                    // Reload the page after a short delay to update the calendar
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorMessage(data.error || 'Failed to mark attendance');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('An error occurred while marking attendance');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
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
});

function showView(view) {
    var currentView = document.getElementById('current-view');
    var detailedView = document.getElementById('detailed-view');
    var currentBtn = document.getElementById('current-view-btn');
    var detailedBtn = document.getElementById('detailed-view-btn');
    
    if (view === 'current') {
        currentView.style.display = 'block';
        detailedView.style.display = 'none';
        currentBtn.className = 'bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors hover:bg-blue-700 cursor-pointer shadow-sm';
        detailedBtn.className = 'bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors hover:bg-gray-300 cursor-pointer shadow-sm';
    } else {
        currentView.style.display = 'none';
        detailedView.style.display = 'block';
        currentBtn.className = 'bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors hover:bg-gray-300 cursor-pointer shadow-sm';
        detailedBtn.className = 'bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors hover:bg-blue-700 cursor-pointer shadow-sm';
    }
}

function toggleDetailedView() {
    var detailedView = document.getElementById('detailed-calendar-view');
    var toggleText = document.getElementById('toggle-text');
    
    if (detailedView.style.display === 'none' || detailedView.style.display === '') {
        detailedView.style.display = 'block';
        toggleText.textContent = 'Hide Detailed Calendar View';
    } else {
        detailedView.style.display = 'none';
        toggleText.textContent = 'Show Detailed Calendar View';
    }
}
</script>
@endsection
