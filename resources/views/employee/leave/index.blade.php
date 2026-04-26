@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Leave Requests</h1>
        <p class="text-gray-600 mt-2">Manage employee leave requests</p>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-10 gap-6">
        
        <!-- LEFT COLUMN - Leave Calendar -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 col-span-3">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Leave Calendar
                </h2>
                <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-1">
                    <button onclick="changeMonth(-1)" class="p-2 hover:bg-white hover:shadow-sm rounded-md transition-all duration-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <span id="currentMonth" class="text-sm font-semibold text-gray-800 px-3 py-1 min-w-[120px] text-center">February 2026</span>
                    <button onclick="changeMonth(1)" class="p-2 hover:bg-white hover:shadow-sm rounded-md transition-all duration-200">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="mb-6">
                <div class="grid grid-cols-7 gap-px bg-gray-300 rounded-lg overflow-hidden">
                    <!-- Weekday headers -->
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">SUN</div>
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">MON</div>
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">TUE</div>
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">WED</div>
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">THU</div>
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">FRI</div>
                    <div class="bg-gray-100 text-center text-xs font-semibold text-gray-700 py-2">SAT</div>
                </div>
                <div id="calendarGrid" class="grid grid-cols-7 gap-px bg-gray-300 rounded-lg overflow-hidden">
                    <!-- Calendar days will be populated by JavaScript -->
                </div>
            </div>

            <!-- Legend -->
            <div class="border-t border-gray-200 pt-4 mt-4">
                <div class="flex items-center justify-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-orange-100 rounded-full border-2 border-orange-300"></div>
                        <span class="text-gray-600">Pending: 3</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-100 rounded-full border-2 border-green-300"></div>
                        <span class="text-gray-600">Approved: 1</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-100 rounded-full border-2 border-red-300"></div>
                        <span class="text-gray-600">Rejected: 1</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN - Leave Requests -->
        <div class="space-y-6 col-span-7">
            
            <!-- Pending Requests Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Pending Requests</h2>
                    <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">3</span>
                </div>

                <div class="space-y-4">
                    <!-- Pending Request 1 -->
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                JD
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">John Doe</h3>
                                        <p class="text-sm text-gray-600">Senior Developer</p>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Date:</span> Feb 11-12, 2026
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Duration:</span> 2 days
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> Personal family function out of station
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Applied on Feb 8, 2026
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button class="px-3 py-1.5 border border-red-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors cursor-pointer">
                                            Reject
                                        </button>
                                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                                            Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Request 2 -->
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                SM
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Sarah Miller</h3>
                                        <p class="text-sm text-gray-600">HR Manager</p>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Date:</span> Feb 25, 2026
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Duration:</span> 1 day
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> Medical appointment
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Applied on Feb 20, 2026
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button class="px-3 py-1.5 border border-red-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors cursor-pointer">
                                            Reject
                                        </button>
                                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                                            Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Request 3 -->
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                RJ
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Robert Johnson</h3>
                                        <p class="text-sm text-gray-600">Marketing Lead</p>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Date:</span> Feb 26, 2026
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Duration:</span> 1 day
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> Child's school annual day
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Applied on Feb 22, 2026
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button class="px-3 py-1.5 border border-red-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors cursor-pointer">
                                            Reject
                                        </button>
                                        <button class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                                            Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent History Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Recent History</h2>

                <div class="space-y-4">
                    <!-- Approved Request -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                EW
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Emily Wilson</h3>
                                        <p class="text-sm text-gray-600">Product Designer</p>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Date:</span> Feb 15-17, 2026
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Duration:</span> 3 days
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> Vacation trip to hometown
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Applied on Feb 10, 2026
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            Approved
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Request -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                MB
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Michael Brown</h3>
                                        <p class="text-sm text-gray-600">Sales Executive</p>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Date:</span> Feb 22, 2026
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Duration:</span> 1 day
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> Personal work
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Applied on Feb 18, 2026
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            Rejected
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Request 2 -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                LG
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Lisa Garcia</h3>
                                        <p class="text-sm text-gray-600">Finance Analyst</p>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Date:</span> Feb 5, 2026
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Duration:</span> 1 day
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Reason:</span> Health checkup
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Applied on Feb 1, 2026
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            Approved
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calendar JavaScript
let currentDate = new Date(); // Current date

// Sample leave data
const leaveData = {
    '2026-02-11': { type: 'pending', label: 'Leave' },
    '2026-02-12': { type: 'pending', label: 'Leave' },
    '2026-02-15': { type: 'pending', label: 'Leave' },
    '2026-02-16': { type: 'pending', label: 'Leave' },
    '2026-02-17': { type: 'pending', label: 'Leave' },
    '2026-02-22': { type: 'pending', label: 'Leave' },
    '2026-02-26': { type: 'current', label: 'Today' }
};

function updateCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    const firstDayIndex = firstDay.getDay();
    const lastDayIndex = lastDay.getDay();
    const nextDays = 7 - lastDayIndex - 1;

    // Update month display
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;

    // Clear calendar grid
    const calendarGrid = document.getElementById('calendarGrid');
    calendarGrid.innerHTML = '';

    // Previous month days
    for (let x = firstDayIndex; x > 0; x--) {
        const day = prevLastDay.getDate() - x + 1;
        const dayElement = createDayElement(day, 'prev');
        calendarGrid.appendChild(dayElement);
    }

    // Current month days
    const today = new Date();
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const leaveInfo = leaveData[dateStr];
        const isToday = today.getDate() === i && today.getMonth() === month && today.getFullYear() === year;
        
        let dayType = 'normal';
        if (leaveInfo) {
            dayType = leaveInfo.type;
        } else if (isToday) {
            dayType = 'current';
        }
        
        const dayElement = createDayElement(i, dayType);
        calendarGrid.appendChild(dayElement);
    }

    // Next month days
    for (let j = 1; j <= nextDays; j++) {
        const dayElement = createDayElement(j, 'next');
        calendarGrid.appendChild(dayElement);
    }

    updateLegend();
}

function createDayElement(day, type) {
    const div = document.createElement('div');
    div.className = 'bg-white text-center text-sm font-medium py-2 cursor-pointer transition-colors';
    
    // Add click event for all days to show attendance
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    
    div.onclick = function() {
        showAttendanceModal(dateStr);
    };
    
    switch(type) {
        case 'prev':
        case 'next':
            div.className += ' text-gray-400 bg-gray-50';
            div.textContent = day;
            break;
        case 'current':
            div.className += ' bg-blue-500 text-white font-bold';
            div.textContent = day;
            break;
        case 'pending':
            div.className += ' bg-orange-100 text-orange-700 font-semibold';
            div.textContent = day;
            break;
        case 'approved':
            div.className += ' bg-green-100 text-green-700 font-semibold';
            div.textContent = day;
            break;
        case 'rejected':
            div.className += ' bg-red-100 text-red-700 font-semibold';
            div.textContent = day;
            break;
        default:
            div.className += ' text-gray-700 hover:bg-gray-100';
            div.textContent = day;
            break;
    }
    
    return div;
}

function showAttendanceModal(dateStr) {
    // Format date for display
    const date = new Date(dateStr + 'T00:00:00');
    const formattedDate = date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Create modal HTML
    const modalHtml = `
        <x-modal id="attendanceModal" title="Attendance Details" :show="true" maxWidth="3xl">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Teacher Name</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            <tr>
                                <td colspan="2" class="text-center py-8 text-gray-500">
                                    <div class="flex items-center justify-center">
                                        <svg class="animate-spin h-5 w-5 mr-3 text-blue-600" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading attendance data...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-modal>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Fetch attendance data
    fetch(`/admin/attendance/${dateStr}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const tableBody = doc.querySelector('#attendanceTableBody');
            
            if (tableBody) {
                document.getElementById('attendanceTableBody').innerHTML = tableBody.innerHTML;
            }
        })
        .catch(error => {
            document.getElementById('attendanceTableBody').innerHTML = `
                <tr>
                    <td colspan="2" class="text-center py-8 text-red-500">
                        Error loading attendance data. Please try again.
                    </td>
                </tr>
            `;
        });
}

function closeAttendanceModal() {
    closeModal('attendanceModal');
}

function changeMonth(direction) {
    currentDate.setMonth(currentDate.getMonth() + direction);
    updateCalendar();
}

function updateLegend() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    let pendingCount = 0;
    let approvedCount = 0;
    let rejectedCount = 0;
    
    // Count leaves for current month
    Object.keys(leaveData).forEach(dateStr => {
        const [leaveYear, leaveMonth] = dateStr.split('-').map(Number);
        if (leaveYear === year && leaveMonth === month + 1) {
            const leaveInfo = leaveData[dateStr];
            if (leaveInfo.type === 'pending') pendingCount++;
            else if (leaveInfo.type === 'approved') approvedCount++;
            else if (leaveInfo.type === 'rejected') rejectedCount++;
        }
    });
    
    // Update legend display
    const legendContainer = document.querySelector('.border-t.border-gray-200 .flex.items-center');
    if (legendContainer) {
        legendContainer.innerHTML = `
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-orange-100 rounded-full border-2 border-orange-300"></div>
                <span class="text-gray-600">Pending: ${pendingCount}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-100 rounded-full border-2 border-green-300"></div>
                <span class="text-gray-600">Approved: ${approvedCount}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-100 rounded-full border-2 border-red-300"></div>
                <span class="text-gray-600">Rejected: ${rejectedCount}</span>
            </div>
        `;
    }
}

// Initialize calendar on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCalendar();
});
</script>

@endsection