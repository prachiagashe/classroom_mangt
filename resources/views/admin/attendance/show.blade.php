@extends('layouts.app')

@section('title', "Attendance - $class")

@section('content')
<style>
    /* Custom styles for the toggle switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ef4444; /* red-500 (Absent) */
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #22c55e; /* green-500 (Present) */
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }

    /* Checkbox state colors */
    .student-checkbox:checked {
        accent-color: #22c55e;
    }
    .student-checkbox:not(:checked) {
        accent-color: #ef4444;
        background-color: #fee2e2;
    }
</style>

<div class="max-w-7xl mx-auto p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.attendance.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                            Attendance
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-900 font-medium md:ml-2">{{ $class }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">{{ $class }} - Student List</h1>
            <p class="text-gray-500 mt-1">Today is <strong>{{ $today->format('d F Y (l)') }}</strong></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.attendance.save') }}" method="POST" id="attendanceForm">
            @csrf
            <input type="hidden" name="class" value="{{ $class }}">
            
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-20 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <label for="selectAll" class="cursor-pointer">SELECT</label>
                                <input type="checkbox" id="selectAll" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                            </div>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">
                            Roll Number
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Student Name
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-40 text-center">
                            STATUS
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($students as $student)
                        @php
                            // Get the status from the database if it exists
                            $dbStatus = $existingAttendance[$student->roll_number] ?? null;
                            
                            // For the initial form state, only check if already 'P' in DB
                            $isChecked = ($dbStatus === 'P');
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="attendance[]" value="{{ $student->roll_number }}" 
                                       id="check_{{ $student->roll_number }}"
                                       class="student-checkbox w-6 h-6 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                       {{ $isChecked ? 'checked' : '' }}>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">
                                {{ $student->roll_number }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 uppercase">
                                <label for="check_{{ $student->roll_number }}" class="cursor-pointer block">
                                    {{ $student->student_name }}
                                </label>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    <label class="switch">
                                        <input type="checkbox" onchange="syncStatus('{{ $student->roll_number }}', this)" 
                                               id="toggle_{{ $student->roll_number }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                    <span id="label_{{ $student->roll_number }}" 
                                          class="text-[10px] font-bold uppercase py-1 px-3 rounded-md 
                                          @if($dbStatus === 'P')
                                              text-green-700 bg-green-100
                                          @elseif($dbStatus === 'A')
                                              text-red-700 bg-red-100
                                          @else
                                              text-gray-700 bg-gray-100
                                          @endif">
                                        {{ $dbStatus ? ($dbStatus === 'P' ? 'PRESENT' : 'ABSENT') : 'NOT MARKED' }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0">
                    Save Attendance
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Calendar (Monthly Register) -->
    <div class="mt-12 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between bg-gray-50 gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 border-l-4 border-blue-600 pl-3">Monthly Attendance Register</h2>
                <p class="text-xs text-gray-500 ml-4 mt-1">{{ $monthYear }} @if(isset($selectedDate) && $selectedDate->format('Y-m') !== $today->format('Y-m'))(Selected Month)@else (Current Month Detailed View)@endif</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- Month and Year Filters -->
                <div class="flex items-center gap-3">
                    <label for="monthFilter" class="text-xs font-medium text-gray-700">Month:</label>
                    <select id="monthFilter" name="monthFilter" class="text-xs border border-gray-300 rounded-md px-2 py-2 focus:ring-blue-500 focus:border-blue-500">
                        @php
                            $currentMonth = $today->format('m');
                            $selectedMonth = isset($selectedDate) ? $selectedDate->format('m') : $currentMonth;
                            $months = [
                                '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                                '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                                '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                            ];
                        @endphp
                        @foreach($months as $monthNum => $monthName)
                            @php
                                $selected = ($monthNum == $selectedMonth) ? 'selected' : '';
                            @endphp
                            <option value="{{ $monthNum }}" {{ $selected }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                    
                    <label for="yearFilter" class="text-xs font-medium text-gray-700">Year:</label>
                    <select id="yearFilter" name="yearFilter" class="text-xs border border-gray-300 rounded-md px-2 py-2 focus:ring-blue-500 focus:border-blue-500">
                        @php
                            $currentYear = $today->format('Y');
                            $selectedYear = isset($selectedDate) ? $selectedDate->format('Y') : $currentYear;
                            $years = range($currentYear - 2, $currentYear + 1);
                        @endphp
                        @foreach($years as $year)
                            @php
                                $selected = ($year == $selectedYear) ? 'selected' : '';
                            @endphp
                            <option value="{{ $year }}" {{ $selected }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    
                    <button onclick="filterByMonthYear()" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition-colors">
                        Filter
                    </button>
                </div>
                <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-green-500 rounded-sm"></span> <span class="text-green-700">Present</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-red-500 rounded-sm"></span> <span class="text-red-700">Absent</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-yellow-100 rounded-sm border border-yellow-200"></span> <span class="text-yellow-600">Sunday</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto relative">
            <table class="w-full text-left border-collapse text-[11px]">
                <thead>
                    <tr class="bg-blue-50/50 border-b border-gray-200">
                        <th class="px-4 py-4 border-r border-gray-200 sticky left-0 bg-blue-50 z-20 w-24 whitespace-nowrap font-bold text-blue-900">ROLL NO</th>
                        <th class="px-4 py-4 border-r border-gray-200 sticky left-[96px] bg-blue-50 z-20 min-w-[180px] whitespace-nowrap font-bold text-blue-900">STUDENT NAME</th>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $baseDate = isset($selectedDate) ? $selectedDate : $today;
                                $date = \Carbon\Carbon::parse($baseDate->format('Y-m-') . $i);
                                $isSunday = $date->isSunday();
                                $isToday = ($i == $today->day && isset($selectedDate) && $selectedDate->format('Y-m') === $today->format('Y-m'));
                            @endphp
                            <th class="px-2 py-4 text-center border-r border-gray-200 min-w-[35px] 
                                {{ $isSunday ? 'bg-yellow-50 text-yellow-800' : 'text-gray-600' }}
                                {{ $isToday ? 'ring-2 ring-inset ring-blue-500 bg-blue-100/50' : '' }}">
                                {{ $i }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($students as $student)
                        @php
                            $attendance = $monthlyAttendance->get($student->roll_number);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-4 py-3 border-r border-gray-200 sticky left-0 bg-white group-hover:bg-gray-50 z-10 font-mono text-gray-500">
                                {{ $student->roll_number }}
                            </td>
                            <td class="px-4 py-3 border-r border-gray-200 sticky left-[96px] bg-white group-hover:bg-gray-50 z-10 font-bold text-gray-900 whitespace-nowrap uppercase">
                                {{ $student->student_name }}
                            </td>
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                    $dayCol = "day_" . $i;
                                    $status = $attendance ? $attendance->$dayCol : null;
                                    $baseDate = isset($selectedDate) ? $selectedDate : $today;
                                    $date = \Carbon\Carbon::parse($baseDate->format('Y-m-') . $i);
                                    $isSunday = $date->isSunday();
                                    $isToday = ($i == $today->day && isset($selectedDate) && $selectedDate->format('Y-m') === $today->format('Y-m'));
                                @endphp
                                <td class="p-1 border-r border-gray-200 text-center {{ $isSunday ? 'bg-yellow-50/50' : '' }} {{ $isToday ? 'bg-blue-50/20' : '' }}">
                                    @if($isSunday)
                                        <span class="text-yellow-600 font-bold text-[10px]">S</span>
                                    @elseif($status === 'P')
                                        <div class="w-6 h-6 bg-green-500 text-white rounded-md flex items-center justify-center mx-auto text-[10px] font-black shadow-sm ring-1 ring-white">P</div>
                                    @elseif($status === 'A')
                                        <div class="w-6 h-6 bg-red-500 text-white rounded-md flex items-center justify-center mx-auto text-[10px] font-black shadow-sm ring-1 ring-white">A</div>
                                    @else
                                        <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-[10px] text-gray-500">
            <div class="flex items-center gap-4">
                <span>P = Present</span>
                <span>A = Absent</span>
                <span>S = Sunday</span>
                <span>- = Not Marked</span>
            </div>
            <div class="italic">
                * Scroll horizontally to view all days of the month.
            </div>
        </div>
    </form>
    </div>
</div>

<script>
    // Filter attendance by month and year
    function filterByMonthYear() {
        const month = document.getElementById('monthFilter').value;
        const year = document.getElementById('yearFilter').value;
        const url = new URL(window.location);
        url.searchParams.set('month', year + '-' + month);
        window.location.href = url.toString();
    }

    // Sync the toggle switch and status label with the main checkbox
    function syncStatus(rollNo, toggleEl) {
        const mainCheckbox = document.getElementById('check_' + rollNo);
        const label = document.getElementById('label_' + rollNo);
        
        mainCheckbox.checked = toggleEl.checked;
        
        if (toggleEl.checked) {
            label.innerText = 'PRESENT';
            label.className = 'text-[10px] font-bold uppercase py-1 px-3 rounded-md text-green-700 bg-green-100';
        } else {
            label.innerText = 'ABSENT';
            label.className = 'text-[10px] font-bold uppercase py-1 px-3 rounded-md text-red-700 bg-red-100';
        }
    }

    // Sync main checkbox changes to the toggle switch
    document.querySelectorAll('.student-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const rollNo = this.value;
            const toggle = document.getElementById('toggle_' + rollNo);
            toggle.checked = this.checked;
            syncStatus(rollNo, toggle);
        });
    });

    // Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.checked = isChecked;
            const rollNo = cb.value;
            const toggle = document.getElementById('toggle_' + rollNo);
            toggle.checked = isChecked;
            syncStatus(rollNo, toggle);
        });
    });
</script>
@endsection
