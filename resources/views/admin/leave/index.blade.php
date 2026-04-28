@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
    .animate-fade-in { animation: fadeIn 0.3s ease-in forwards; }
    .no-scroll { overflow: hidden !important; }
    
    .sticky-header th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f9fafb;
    }
    
    .leave-row { transition: all 0.2s ease; }
    .leave-row:hover { transform: scale(1.002); z-index: 5; }

    /* Custom Scrollbar for the table */
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* PTM Style Modal */
    .modal-backdrop-ptm {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .modal-container-ptm {
        background: white;
        border-radius: 12px;
        width: 700px;
        height: 600px;
        max-width: 95vw;
        max-height: 90vh;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .no-scroll {
        overflow: hidden !important;
    }

    /* Custom Vertical Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-blue-600 mb-1">Teacher Attendance & Leave Management</h1>
            <p class="text-[13px] text-gray-500">Manage daily attendance and review leave requests for all employees</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openHolidayModal()" class="flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-100 transition-all border border-indigo-100 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Holiday Declaration
            </button>
            <div class="bg-blue-600 p-3 rounded-xl shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Module Tabs -->
    <div class="mb-6">
        <div class="flex border-b border-gray-200">
            <button onclick="switchModuleTab('attendance')" id="tab-attendance" 
                class="px-6 py-3 text-base font-semibold border-b-2 transition-all duration-300 {{ request()->has('date') || !request()->has('tab') || request()->tab == 'attendance' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Teacher Attendance
            </button>
            <button onclick="switchModuleTab('leaves')" id="tab-leaves" 
                class="px-6 py-3 text-base font-semibold border-b-2 transition-all duration-300 {{ request()->tab == 'leaves' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Leave Requests
            </button>
        </div>
    </div>

    <!-- TEACHER ATTENDANCE TAB CONTENT -->
    <div id="content-attendance" class="{{ request()->has('date') || !request()->has('tab') || request()->tab == 'attendance' ? '' : 'hidden' }}">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-6 mb-6">
                    <form action="{{ route('admin.leave.index') }}" method="GET" class="flex items-center gap-4">
                        <input type="hidden" name="tab" value="attendance">
                        <label for="attendance_date" class="text-sm font-bold text-gray-700">Select Date:</label>
                        <div class="relative">
                            <input type="date" name="date" id="attendance_date" value="{{ $date }}" 
                                onchange="this.form.submit()"
                                class="pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </form>

                    <div class="flex gap-3">
                        <button onclick="filterByStatus('all')" class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg text-xs font-semibold border border-gray-100 hover:bg-gray-100 transition-all cursor-pointer">
                            Total: {{ $employees->count() }}
                        </button>
                        <button onclick="filterByStatus('present')" class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold border border-emerald-100 hover:bg-emerald-100 transition-all cursor-pointer">
                            Present: <span id="count-present">{{ $attendanceRecords->where('status', 'present')->count() }}</span>
                        </button>
                        <button onclick="filterByStatus('absent')" class="flex items-center gap-2 px-3 py-1.5 bg-rose-50 text-rose-700 rounded-lg text-xs font-semibold border border-rose-100 hover:bg-rose-100 transition-all cursor-pointer">
                            Absent: <span id="count-absent">{{ $attendanceRecords->where('status', 'absent')->count() }}</span>
                        </button>
                        <button onclick="filterByStatus('leave')" class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs font-semibold border border-amber-100 hover:bg-amber-100 transition-all cursor-pointer">
                            Leave: <span id="count-leave">{{ $attendanceRecords->where('status', 'leave')->count() }}</span>
                        </button>
                        <button onclick="filterByStatus('holiday')" class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold border border-blue-100 hover:bg-blue-100 transition-all cursor-pointer">
                            Holiday: <span id="count-holiday">{{ $todayHoliday ? $employees->count() : 0 }}</span>
                        </button>
                    </div>
                </div>

                <!-- NEW Filters for Search and Designation -->
                <div class="flex flex-wrap gap-6 items-end border-t border-gray-100 pt-6">
                    <div class="flex-1 min-w-[300px]">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Search Employee</label>
                        <div class="relative">
                            <input type="text" id="attendanceSearch" onkeyup="filterAttendanceTable()"
                                placeholder="Search by Name or Employee ID..." 
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all shadow-sm">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full sm:w-64">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Filter by Designation</label>
                        <select id="designationFilter" onchange="filterAttendanceTable()"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="all">All Designations</option>
                            @foreach($employees->pluck('designation')->unique() as $designation)
                                <option value="{{ strtoupper($designation) }}">{{ strtoupper($designation) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Employee ID</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($employees as $employee)
                                @php
                                    $record = $attendanceRecords->get($employee->employee_code);
                                    $onLeave = in_array($employee->employee_code, $onLeaveToday);
                                    $status = $onLeave ? 'leave' : ($record ? $record->status : 'pending');
                                @endphp
                                <tr class="attendance-row hover:bg-gray-50 transition-colors" 
                                    data-name="{{ strtolower($employee->full_name) }}" 
                                    data-code="{{ strtolower($employee->employee_code) }}"
                                    data-designation="{{ strtoupper($employee->designation) }}"
                                    data-status="{{ $status }}">
                                    <td class="px-6 py-3 whitespace-nowrap font-semibold text-blue-600 text-sm">{{ $employee->employee_code }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3 shadow-sm text-xs">
                                                {{ substr($employee->first_name, 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-gray-900 text-sm">{{ $employee->full_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        <span class="inline-flex px-3 py-1 bg-gray-100 rounded-lg text-xs font-bold uppercase">{{ $employee->designation }}</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap" id="attendance-cell-{{ $employee->employee_code }}">
                                        <div class="flex justify-center items-center h-full">
                                            @php
                                                $record = $attendanceRecords->get($employee->employee_code);
                                                $onLeave = in_array($employee->employee_code, $onLeaveToday);
                                                $todayHoliday = $holidays->where('holiday_date', $date)->first();
                                            @endphp
                                            
                                            @if($todayHoliday)
                                                {{-- Holiday Badge --}}
                                                <span class="px-4 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-bold border border-blue-200 shadow-sm" title="{{ $todayHoliday->reason }}">
                                                    PUBLIC HOLIDAY
                                                </span>
                                            @elseif($onLeave)
                                                {{-- Leave Badge + Disabled Buttons --}}
                                                <div class="flex items-center gap-3">
                                                    <span class="px-4 py-1.5 bg-amber-100 text-amber-800 rounded-lg text-xs font-bold border border-amber-200 shadow-sm">
                                                        LEAVE
                                                    </span>
                                                    <div class="flex gap-2 opacity-50">
                                                        <button disabled class="px-4 py-1.5 bg-white border border-gray-300 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-wider cursor-not-allowed">
                                                            Present
                                                        </button>
                                                        <button disabled class="px-4 py-1.5 bg-white border border-gray-300 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-wider cursor-not-allowed">
                                                            Absent
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Present/Absent Buttons --}}
                                                @php
                                                    $currentStatus = $record ? $record->status : 'pending';
                                                @endphp
                                                <div class="flex gap-2" id="btn-group-{{ $employee->employee_code }}">
                                                    <button id="btn-present-{{ $employee->employee_code }}" 
                                                        onclick="markAttendanceAction('{{ $employee->employee_code }}', 'present')" 
                                                        style="{{ $currentStatus == 'present' ? 'background-color: #10b981; color: white;' : 'background-color: white; color: #10b981; border: 1px solid #10b981;' }}"
                                                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm uppercase tracking-wider">
                                                        Present
                                                    </button>
                                                    <button id="btn-absent-{{ $employee->employee_code }}" 
                                                        onclick="markAttendanceAction('{{ $employee->employee_code }}', 'absent')" 
                                                        style="{{ $currentStatus == 'absent' ? 'background-color: #ef4444; color: white;' : 'background-color: white; color: #ef4444; border: 1px solid #ef4444;' }}"
                                                        class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm uppercase tracking-wider">
                                                        Absent
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

    <!-- LEAVE REQUESTS TAB CONTENT -->
    <div id="content-leaves" class="{{ request()->tab == 'leaves' ? '' : 'hidden' }}">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div onclick="filterLeaves('all')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-all cursor-pointer hover:border-blue-200 group">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Requests</p>
                    <h3 class="text-lg font-bold text-gray-900">{{ $totalRequests }}</h3>
                </div>
            </div>
            <div onclick="filterLeaves('pending')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-all cursor-pointer hover:border-yellow-200 group">
                <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pending</p>
                    <h3 class="text-lg font-bold text-gray-900">{{ $pendingCount }}</h3>
                </div>
            </div>
            <div onclick="filterLeaves('approved')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-all cursor-pointer hover:border-green-200 group">
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Approved</p>
                    <h3 class="text-lg font-bold text-gray-900">{{ $approvedCount }}</h3>
                </div>
            </div>
            <div onclick="filterLeaves('rejected')" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition-all cursor-pointer hover:border-red-200 group">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Rejected</p>
                    <h3 class="text-lg font-bold text-gray-900">{{ $rejectedCount }}</h3>
                </div>
            </div>
        </div>

        <div class="w-full">
                
                <form action="{{ route('admin.leave.index') }}" method="GET" id="leave-filter-form">
                    <input type="hidden" name="tab" value="leaves">
                    <input type="hidden" name="leave_filter" id="leave_filter_input" value="{{ request('leave_filter', 'active') }}">
                    <input type="hidden" name="status_filter" id="status_filter_input" value="{{ request('status_filter', 'all') }}">
                    <input type="hidden" name="quick_filter" id="quick_filter_input" value="{{ request('quick_filter') }}">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                        <div class="flex flex-col gap-4">
                            <!-- Search & Quick Filters -->
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="relative flex-1 min-w-[300px]">
                                    <input type="text" name="search_employee" value="{{ request('search_employee') }}"
                                        placeholder="Search by teacher name..." 
                                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-transparent focus:bg-white focus:border-blue-500 rounded-xl transition-all outline-none text-sm font-medium">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="submitQuickFilter('today')" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('quick_filter') == 'today' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white' }}">TODAY</button>
                                    <button type="button" onclick="submitQuickFilter('this-week')" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('quick_filter') == 'this-week' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white' }}">THIS WEEK</button>
                                    <button type="button" onclick="submitQuickFilter('emergency')" 
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('quick_filter') == 'emergency' ? 'bg-red-600 text-white shadow-sm' : 'bg-red-50 text-red-600 hover:bg-red-600 hover:text-white' }}">EMERGENCY</button>
                                </div>
                            </div>

                            <!-- Status & Action Bar -->
                            <div class="flex flex-wrap items-center justify-between gap-4 border-t border-gray-50 pt-4">
                                <div class="flex bg-gray-100 p-1 rounded-xl">
                                    <button type="button" onclick="submitStatusFilter('all')" 
                                        class="filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status_filter', 'all') == 'all' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">ALL</button>
                                    <button type="button" onclick="submitStatusFilter('pending')" 
                                        class="filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status_filter') == 'pending' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">PENDING</button>
                                    <button type="button" onclick="submitStatusFilter('approved')" 
                                        class="filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status_filter') == 'approved' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">APPROVED</button>
                                    <button type="button" onclick="submitStatusFilter('rejected')" 
                                        class="filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('status_filter') == 'rejected' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">REJECTED</button>
                                </div>

                                <!-- Archive Toggle -->
                                <div class="flex bg-gray-100 p-1 rounded-xl">
                                    <button type="button" onclick="submitLeaveFilter('active')" 
                                        class="filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('leave_filter', 'active') == 'active' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">ACTIVE</button>
                                    <button type="button" onclick="submitLeaveFilter('archived')" 
                                        class="filter-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('leave_filter') == 'archived' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">ARCHIVED</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <script>
                function submitQuickFilter(filter) {
                    const input = document.getElementById('quick_filter_input');
                    input.value = input.value === filter ? '' : filter;
                    document.getElementById('leave-filter-form').submit();
                }
                function submitStatusFilter(status) {
                    document.getElementById('status_filter_input').value = status;
                    document.getElementById('leave-filter-form').submit();
                }
                function submitLeaveFilter(filter) {
                    document.getElementById('leave_filter_input').value = filter;
                    document.getElementById('leave-filter-form').submit();
                }
                </script>
                </div>

                <!-- Leave Requests Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    @if($leaveRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="crm-table">
                                <thead class="sticky top-0 z-10">
                                    <tr>
                                        <th class="w-10">
                                            <input type="checkbox" id="selectAllLeaves" onclick="toggleAllLeaves(this)" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </th>
                                        <th>Teacher Name</th>
                                        <th>Type</th>
                                        <th>Date Range</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveRequests as $request)
                                        <tr class="leave-row 
                                            @if(request('leave_id') == $request->id) bg-blue-50/50 border-2 border-blue-400 @endif
                                            @if($request->leave_type === 'emergency') border-l-4 border-l-red-500
                                            @elseif($request->leave_type === 'sick') border-l-4 border-l-yellow-500
                                            @else border-l-4 border-l-blue-500 @endif" 
                                            id="leave-request-{{ $request->id }}" 
                                            data-status="{{ $request->status }}"
                                            data-name="{{ strtolower($request->employee->full_name) }}"
                                            data-type="{{ strtolower($request->leave_type) }}"
                                            data-start="{{ $request->start_date }}"
                                            data-end="{{ $request->end_date }}"
                                            data-reason="{{ $request->reason }}"
                                            data-attachment="{{ $request->attachment ? asset('storage/' . $request->attachment) : '' }}"
                                            data-id="{{ $request->id }}">
                                            <td>
                                                @if($request->status === 'pending')
                                                    <input type="checkbox" name="leave_ids[]" value="{{ $request->id }}" 
                                                        onclick="updateBulkActionState()"
                                                        class="leave-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="crm-avatar bg-gradient-to-br from-blue-500 to-indigo-600 mr-3">
                                                        {{ substr($request->employee->first_name, 0, 1) }}{{ substr($request->employee->surname, 0, 1) }}
                                                    </div>
                                                    <span class="font-bold text-gray-900">{{ $request->employee->full_name }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap">
                                                <span class="crm-badge 
                                                    @if($request->leave_type === 'emergency') crm-badge-danger
                                                    @elseif($request->leave_type === 'sick') crm-badge-warning
                                                    @else crm-badge-info @endif">
                                                    {{ $request->leave_type }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap">
                                                <div class="font-bold text-gray-700">{{ date('d M', strtotime($request->start_date)) }} - {{ date('d M', strtotime($request->end_date)) }}</div>
                                                <div class="secondary-text">{{ Carbon\Carbon::parse($request->start_date)->diffInDays(Carbon\Carbon::parse($request->end_date)) + 1 }} Days</div>
                                            </td>
                                            <td>
                                                <p class="secondary-text max-w-[250px] cursor-pointer hover:text-blue-600 transition-colors" 
                                                    title="{{ $request->reason }}" 
                                                    onclick="viewLeaveDetails(this.closest('tr').querySelector('button[title=\'View Details\']'))">
                                                    {{ Str::limit($request->reason, 50, '...') }}
                                                </p>
                                            </td>
                                            <td class="whitespace-nowrap">
                                                <span class="crm-badge
                                                    @if($request->status === 'pending') crm-badge-warning
                                                    @elseif($request->status === 'approved') crm-badge-success
                                                    @else crm-badge-danger @endif">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    <button onclick="viewLeaveDetails(this)" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View Details">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </button>
                                                    
                                                    @if($request->status === 'pending')
                                                        <form action="{{ route('admin.leave.approve', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this leave?')">
                                                            @csrf
                                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Approve">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            </button>
                                                        </form>
                                                        <button onclick="openRejectModal({{ $request->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Reject">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4 px-4 py-3 bg-white border-t border-gray-100 flex items-center justify-between sm:px-6">
                            {{ $leaveRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-20">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">No Leave Requests</h4>
                            <p class="text-sm text-gray-500">There are no leave requests to show for the selected filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
<div id="holidayModal" class="modal-backdrop-ptm" onclick="if(event.target === this) closeHolidayModal()">
    <div class="modal-container-ptm animate-fade-in-up">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">Holiday Declaration</h3>
            <button onclick="closeHolidayModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left: Calendar Selection -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Select Date</h4>
                        <div class="flex items-center gap-1">
                            <button onclick="changeMonth(-1)" class="p-1.5 hover:bg-gray-100 rounded-md transition-all">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <span id="currentMonth" class="text-xs font-bold text-gray-800 px-2 min-w-[100px] text-center">Loading...</span>
                            <button onclick="changeMonth(1)" class="p-1.5 hover:bg-gray-100 rounded-md transition-all">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-px bg-gray-100 rounded-lg overflow-hidden border border-gray-100">
                        @foreach(['SUN','MON','TUE','WED','THU','FRI','SAT'] as $day)
                            <div class="bg-gray-50 text-center text-[10px] font-bold text-gray-400 py-2">{{ $day }}</div>
                        @endforeach
                        <div id="calendarGrid" class="contents">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <div id="holidayListContainer" class="mt-4 pt-4 border-t border-gray-100">
                        <!-- Populated by JS -->
                        <p class="text-[10px] text-gray-400 text-center italic">Select a date from calendar</p>
                    </div>
                </div>

                <!-- Right: Form Area -->
                <div class="space-y-4">
                    <form id="holidayForm" onsubmit="return false;">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Selected Date</label>
                                <input type="hidden" id="selectedHolidayDate" name="holiday_date">
                                <input type="text" id="displayHolidayDate" readonly
                                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg font-bold text-blue-600 outline-none focus:border-blue-500 transition-all"
                                    placeholder="Select from calendar">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Holiday Reason / Title</label>
                                <textarea id="holidayReason" name="reason" rows="4" required
                                    class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg font-medium text-gray-900 outline-none focus:border-blue-500 transition-all"
                                    placeholder="e.g. Diwali Festival"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
            <button onclick="closeHolidayModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </button>
            <button onclick="document.getElementById('holidayForm').dispatchEvent(new Event('submit'))" id="saveHolidayBtn" disabled
                class="px-8 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg transform hover:-translate-y-0.5 active:translate-y-0 font-bold">
                Declare Holiday
            </button>
        </div>
    </div>
</div>

<!-- Leave Detail Modal -->
<div id="leaveDetailModal" class="modal-backdrop-ptm" onclick="if(event.target === this) closeLeaveDetailModal()">
    <div class="modal-container-ptm animate-fade-in-up" style="max-width: 600px;">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Leave Details</h3>
            <button onclick="closeLeaveDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <div id="detailAvatar" class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white text-lg font-bold shadow-md"></div>
                <div>
                    <h4 id="detailName" class="text-base font-bold text-gray-900"></h4>
                    <span id="detailStatus" class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider mt-1"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Leave Type</label>
                    <p id="detailType" class="text-sm font-bold text-gray-900 uppercase"></p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Duration</label>
                    <p id="detailRange" class="text-sm font-bold text-gray-900"></p>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Reason for Leave</label>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p id="detailReason" class="text-sm text-gray-700 leading-relaxed font-medium"></p>
                </div>
            </div>

            <div id="detailAttachmentSection" class="hidden">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Attachment</label>
                <a id="detailAttachmentLink" href="#" target="_blank" class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all group">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-sm font-bold">View Document</span>
                </a>
            </div>
        </div>
        <div id="detailActions" class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <!-- Dynamic Actions -->
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal-backdrop-ptm" onclick="if(event.target === this) closeRejectModal()">
    <div class="modal-container-ptm animate-fade-in-up" style="max-width: 500px;">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Reject Leave Request</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.leave.reject', ':id') }}" method="POST" id="rejectForm">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label for="admin_remark" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea name="admin_remark" id="admin_remark" rows="4" required
                        placeholder="Please provide a reason for rejection..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none text-sm"></textarea>
                </div>
            </div>
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-800 transition-all">CANCEL</button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg text-sm font-bold shadow-lg shadow-red-100 hover:bg-red-700 transition-all">REJECT LEAVE</button>
            </div>
        </form>
    </div>
</div>

<script>
// Tab Switching
function switchModuleTab(tab) {
    const atTab = document.getElementById('tab-attendance');
    const lvTab = document.getElementById('tab-leaves');
    const atCon = document.getElementById('content-attendance');
    const lvCon = document.getElementById('content-leaves');

    if (tab === 'attendance') {
        atTab.classList.add('border-blue-600', 'text-blue-600');
        atTab.classList.remove('border-transparent', 'text-gray-500');
        lvTab.classList.add('border-transparent', 'text-gray-500');
        lvTab.classList.remove('border-blue-600', 'text-blue-600');
        atCon.classList.remove('hidden');
        lvCon.classList.add('hidden');
    } else {
        lvTab.classList.add('border-blue-600', 'text-blue-600');
        lvTab.classList.remove('border-transparent', 'text-gray-500');
        atTab.classList.add('border-transparent', 'text-gray-500');
        atTab.classList.remove('border-blue-600', 'text-blue-600');
        lvCon.classList.remove('hidden');
        atCon.classList.add('hidden');
        
        // Re-initialize calendar when switching to leaves tab
        if(typeof updateCalendar === 'function') updateCalendar();
    }
}

// Global State
let currentDate = new Date();
const leaveRequests = @json($leaveRequests);

// Calendar functionality
function changeMonth(direction) {
    currentDate.setMonth(currentDate.getMonth() + direction);
    updateCalendar();
}

function updateCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    
    const firstDayIndex = firstDay.getDay();
    const lastDayIndex = lastDay.getDay();
    const nextDays = 7 - lastDayIndex - 1;
    
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    
    const monthEl = document.getElementById('currentMonth');
    if(monthEl) monthEl.textContent = `${monthNames[month]} ${year}`;
    
    const holidays = @json($holidays);
    const holidayListContainer = document.getElementById('holidayListContainer');
    let holidayListHTML = '';
    
    // Filter holidays for the current visible month and year
    const currentMonthHolidays = holidays.filter(h => {
        const hDate = new Date(h.holiday_date);
        return hDate.getMonth() === month && hDate.getFullYear() === year;
    });

    if (currentMonthHolidays.length > 0) {
        holidayListHTML = `<h5 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-3">Holidays in ${monthNames[month]} ${year}</h5><div class="space-y-2 max-h-[200px] overflow-y-auto pr-2 custom-scrollbar">`;
        currentMonthHolidays.sort((a, b) => new Date(a.holiday_date) - new Date(b.holiday_date))
                .forEach(h => {
                    holidayListHTML += `
                        <div class="flex items-center gap-2 p-2 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <span class="text-lg">🎉</span>
                            <div>
                                <p class="text-[11px] font-black text-gray-900">${h.holiday_date}</p>
                                <p class="text-[10px] font-bold text-gray-500">${h.reason}</p>
                            </div>
                        </div>
                    `;
                });
        holidayListHTML += `</div>`;
    } else {
        holidayListHTML = `<p class="text-[10px] text-gray-400 text-center font-medium italic">No holidays declared for ${monthNames[month]} ${year}</p>`;
    }
    
    if(holidayListContainer) holidayListContainer.innerHTML = holidayListHTML;

    let days = '';
    
    for (let x = firstDayIndex; x > 0; x--) {
        days += `<div class="bg-gray-50/50 text-gray-300 text-center py-4 text-xs font-bold border-gray-100">${prevLastDay.getDate() - x + 1}</div>`;
    }
    
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const isToday = (i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear());
        
        // Count leaves for this date
        const leaveCount = leaveRequests.filter(req => {
            return req.status === 'approved' && dateStr >= req.start_date && dateStr <= req.end_date;
        }).length;

        // Check if it's a holiday
        const holiday = holidays.find(h => h.holiday_date === dateStr);

        let dayClass = 'bg-white text-gray-900 text-center py-4 text-sm hover:bg-blue-50 cursor-pointer relative transition-all group border-gray-100';
        if (isToday) dayClass += ' bg-blue-50/50 ring-1 ring-inset ring-blue-600 font-black';
        if (holiday) dayClass += ' bg-indigo-50 !text-indigo-700 font-bold';
        
        days += `
            <div class="${dayClass}" onclick="handleDateClick('${dateStr}')" title="${holiday ? 'Holiday: ' + holiday.reason : ''}">
                <span class="relative z-10">${i}</span>
                ${holiday ? `<div class="absolute top-1 right-1 text-[10px]">🎈</div>` : ''}
                ${leaveCount > 0 ? `
                    <div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 flex gap-0.5">
                        <span class="w-1 h-1 rounded-full bg-red-500"></span>
                        <span class="text-[8px] font-black text-red-600">${leaveCount}</span>
                    </div>
                ` : ''}
                <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-5 transition-opacity"></div>
            </div>`;
    }
    
    for (let j = 1; j <= nextDays; j++) {
        days += `<div class="bg-gray-50/50 text-gray-300 text-center py-4 text-xs font-bold border-gray-100">${j}</div>`;
    }
    
    const grid = document.getElementById('calendarGrid');
    if(grid) grid.innerHTML = days;
}

function handleDateClick(date) {
    const holidayModal = document.getElementById('holidayModal');
    if (holidayModal.style.display === 'flex') {
        const holidays = @json($holidays);
        const existingHoliday = holidays.find(h => h.holiday_date === date);
        
        if (existingHoliday) {
            alert(`Duplicate Holiday: ${date} is already declared as "${existingHoliday.reason}"`);
            document.getElementById('selectedHolidayDate').value = date;
            document.getElementById('displayHolidayDate').value = `${date} (${existingHoliday.reason})`;
            document.getElementById('holidayReason').value = existingHoliday.reason;
            document.getElementById('saveHolidayBtn').disabled = false;
        } else {
            document.getElementById('selectedHolidayDate').value = date;
            document.getElementById('displayHolidayDate').value = date;
            document.getElementById('holidayReason').value = '';
            document.getElementById('saveHolidayBtn').disabled = false;
        }
        
        updateCalendar();
    } else {
        const atCon = document.getElementById('content-attendance');
        if (atCon.classList.contains('hidden')) {
            filterLeavesByDate(date);
        } else {
            window.location.href = `{{ route('admin.leave.index') }}?date=${date}&tab=attendance`;
        }
    }
}

function openHolidayModal() {
    document.getElementById('holidayModal').style.display = 'flex';
    document.body.classList.add('no-scroll');
    updateCalendar();
}

function closeHolidayModal() {
    document.getElementById('holidayModal').style.display = 'none';
    document.body.classList.remove('no-scroll');
    document.getElementById('holidayForm').reset();
    document.getElementById('selectedHolidayDate').value = '';
    document.getElementById('displayHolidayDate').value = '';
    document.getElementById('saveHolidayBtn').disabled = true;
}

// Holiday Form Submission
document.getElementById('holidayForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('saveHolidayBtn');
    const originalText = btn.innerText;
    
    btn.disabled = true;
    btn.innerText = 'SAVING...';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch("{{ route('admin.leave.holiday') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message);
            window.location.reload();
        } else {
            alert('Error: ' + result.message);
            btn.disabled = false;
            btn.innerText = originalText;
        }
    } catch (error) {
        console.error('Holiday Save Error:', error);
        alert('An error occurred.');
        btn.disabled = false;
        btn.innerText = originalText;
    }
});

function filterLeavesByDate(selectedDate) {
    const rows = document.querySelectorAll('.leave-row');
    
    rows.forEach(row => {
        const start = row.dataset.start;
        const end = row.dataset.end;
        if (selectedDate >= start && selectedDate <= end) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    resetFilterButtons();
}

// Leave Table Filtering & Search
function filterLeaveTable() {
    const searchTerm = document.getElementById('leaveSearch').value.toLowerCase();
    const rows = document.querySelectorAll('.leave-row');
    
    rows.forEach(row => {
        const name = row.dataset.name;
        if (name.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterLeaves(status) {
    const rows = document.querySelectorAll('.leave-row');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button visual states
    buttons.forEach(btn => {
        const btnStatus = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
        if (btnStatus === status) {
            btn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btn.classList.remove('text-gray-500');
        } else {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btn.classList.add('text-gray-500');
        }
    });
    
    // Filter rows
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function quickFilter(type) {
    const rows = document.querySelectorAll('.leave-row');
    const today = new Date().toISOString().split('T')[0];
    const weekAgo = new Date();
    weekAgo.setDate(weekAgo.getDate() - 7);
    const weekAgoStr = weekAgo.toISOString().split('T')[0];

    rows.forEach(row => {
        const start = row.dataset.start;
        const end = row.dataset.end;
        const leaveType = row.dataset.type;

        if (type === 'today') {
            row.style.display = (today >= start && today <= end) ? '' : 'none';
        } else if (type === 'this-week') {
            row.style.display = (start >= weekAgoStr) ? '' : 'none';
        } else if (type === 'emergency') {
            row.style.display = (leaveType === 'emergency') ? '' : 'none';
        }
    });
}

function resetFilterButtons() {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
        btn.classList.add('text-gray-500');
    });
}

// Detail Modal
function viewLeaveDetails(btn) {
    const row = btn.closest('.leave-row');
    const data = row.dataset;
    
    const avatar = document.getElementById('detailAvatar');
    const name = document.getElementById('detailName');
    const status = document.getElementById('detailStatus');
    const type = document.getElementById('detailType');
    const range = document.getElementById('detailRange');
    const reason = document.getElementById('detailReason');
    const attachmentSection = document.getElementById('detailAttachmentSection');
    const attachmentLink = document.getElementById('detailAttachmentLink');
    const actions = document.getElementById('detailActions');

    avatar.textContent = data.name.split(' ').map(n => n[0].toUpperCase()).join('');
    name.textContent = row.querySelector('.font-bold').textContent;
    status.textContent = data.status;
    status.className = `inline-block px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest mt-1 ${
        data.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
        data.status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
    }`;
    type.textContent = data.type;
    range.textContent = `${new Date(data.start).toLocaleDateString('en-GB', {day: '2-digit', month: 'short'})} - ${new Date(data.end).toLocaleDateString('en-GB', {day: '2-digit', month: 'short'})}`;
    reason.textContent = data.reason;

    if (data.attachment) {
        attachmentSection.classList.remove('hidden');
        attachmentLink.href = data.attachment;
    } else {
        attachmentSection.classList.add('hidden');
    }

    actions.innerHTML = '';
    if (data.status === 'pending') {
        actions.innerHTML = `
            <button onclick="handleSingleAction(${data.id}, 'approve')" class="px-6 py-2 bg-green-600 text-white rounded-xl text-sm font-black hover:bg-green-700 transition-all shadow-lg shadow-green-200">APPROVE</button>
            <button onclick="handleSingleAction(${data.id}, 'reject')" class="px-6 py-2 bg-red-600 text-white rounded-xl text-sm font-black hover:bg-red-700 transition-all shadow-lg shadow-red-200">REJECT</button>
        `;
    }

    document.getElementById('leaveDetailModal').style.display = 'flex';
    document.body.classList.add('no-scroll');
}

function closeLeaveDetailModal() {
    document.getElementById('leaveDetailModal').style.display = 'none';
    document.body.classList.remove('no-scroll');
}

function handleSingleAction(id, action) {
    if (action === 'approve') {
        if(confirm('Are you sure you want to approve this leave request?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('admin/leave/approve') }}/${id}`;
            form.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">`;
            document.body.appendChild(form);
            form.submit();
        }
    } else {
        closeLeaveDetailModal();
        openRejectModal(id);
    }
}

// Bulk Actions
function toggleAllLeaves(master) {
    const checkboxes = document.querySelectorAll('.leave-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
    updateBulkActionState();
}

function updateBulkActionState() {
    const selected = document.querySelectorAll('.leave-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const countLabel = document.getElementById('selectedCount');
    
    if (selected.length > 0) {
        bulkActions.classList.remove('hidden');
        countLabel.textContent = `${selected.length} selected`;
    } else {
        bulkActions.classList.add('hidden');
        const selectAll = document.getElementById('selectAllLeaves');
        if(selectAll) selectAll.checked = false;
    }
}

async function handleBulkAction(action) {
    const selected = Array.from(document.querySelectorAll('.leave-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) return;

    let remark = '';
    if (action === 'reject') {
        remark = prompt('Please enter rejection reason for selected requests:', 'Rejected by admin');
        if (remark === null) return;
    } else {
        if (!confirm(`Are you sure you want to approve ${selected.length} leave requests?`)) return;
    }

    const url = action === 'approve' ? "{{ route('admin.leave.bulk_approve') }}" : "{{ route('admin.leave.bulk_reject') }}";
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ids: selected, admin_remark: remark })
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message);
            window.location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Bulk Action Error:', error);
        alert('An error occurred during bulk operation.');
    }
}

// Attendance Table Filtering
let currentStatusFilter = 'all';

function filterByStatus(status) {
    currentStatusFilter = status;
    filterAttendanceTable();
}

function filterAttendanceTable() {
    const searchTerm = document.getElementById('attendanceSearch').value.toLowerCase();
    const designation = document.getElementById('designationFilter').value;
    const rows = document.querySelectorAll('.attendance-row');

    rows.forEach(row => {
        const nameMatch = row.dataset.name.includes(searchTerm) || row.dataset.code.includes(searchTerm);
        const designationMatch = designation === 'all' || row.dataset.designation === designation;
        const statusMatch = currentStatusFilter === 'all' || row.dataset.status === currentStatusFilter;
        
        row.style.display = (nameMatch && designationMatch && statusMatch) ? '' : 'none';
    });
}

// Mark Attendance Action (AJAX)
function markAttendanceAction(employeeCode, status) {
    const btnPresent = document.getElementById(`btn-present-${employeeCode}`);
    const btnAbsent = document.getElementById(`btn-absent-${employeeCode}`);
    const row = document.querySelector(`.attendance-row[data-code="${employeeCode.toLowerCase()}"]`);
    const dateInput = document.getElementById('attendance_date');
    const date = dateInput ? dateInput.value : new Date().toISOString().split('T')[0];

    if (!btnPresent || !btnAbsent) return;

    // Disable buttons temporarily
    btnPresent.disabled = true;
    btnAbsent.disabled = true;

    fetch("{{ route('admin.leave.mark-single') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ employee_code: employeeCode, date: date, status: status })
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok && data.success) {
            // Update row status
            if (row) row.dataset.status = status;
            
            // Update buttons classes
            if (status === 'present') {
                btnPresent.style.backgroundColor = "#10b981";
                btnPresent.style.color = "white";
                btnPresent.style.border = "none";
                
                btnAbsent.style.backgroundColor = "white";
                btnAbsent.style.color = "#ef4444";
                btnAbsent.style.border = "1px solid #ef4444";
            } else {
                btnAbsent.style.backgroundColor = "#ef4444";
                btnAbsent.style.color = "white";
                btnAbsent.style.border = "none";
                
                btnPresent.style.backgroundColor = "white";
                btnPresent.style.color = "#10b981";
                btnPresent.style.border = "1px solid #10b981";
            }
            
            updateLiveCountsManual();
        } else {
            alert("Error: " + (data.message || "Failed to mark attendance."));
        }
    })
    .catch(error => {
        console.error(error);
        alert("An error occurred.");
    })
    .finally(() => {
        btnPresent.disabled = false;
        btnAbsent.disabled = false;
    });
}

function updateLiveCountsManual() {
    const rows = document.querySelectorAll('.attendance-row');
    let present = 0, absent = 0, leave = 0;

    rows.forEach(row => {
        const status = row.dataset.status;
        if (status === 'present') present++;
        else if (status === 'absent') absent++;
        else if (status === 'leave') leave++;
    });

    const isHoliday = document.querySelector('.attendance-row .bg-blue-100') !== null;
    const holidayCount = isHoliday ? rows.length : 0;

    if(document.getElementById('count-present')) document.getElementById('count-present').textContent = present;
    if(document.getElementById('count-absent')) document.getElementById('count-absent').textContent = absent;
    if(document.getElementById('count-leave')) document.getElementById('count-leave').textContent = leave;
    if(document.getElementById('count-holiday')) document.getElementById('count-holiday').textContent = holidayCount;
}

function openRejectModal(id) {
    document.getElementById('rejectModal').style.display = 'flex';
    document.body.classList.add('no-scroll');
    document.getElementById('rejectForm').action = "{{ route('admin.leave.reject', ':id') }}".replace(':id', id);
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.body.classList.remove('no-scroll');
    const remarkInput = document.getElementById('admin_remark');
    if(remarkInput) remarkInput.value = '';
}

document.addEventListener('DOMContentLoaded', function() {
    updateCalendar();
    updateLiveCountsManual();
    
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'leaves') switchModuleTab('leaves');
});
// Close on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeHolidayModal();
        closeRejectModal();
        closeLeaveDetailModal();
    }
});
</script>
@endpush
