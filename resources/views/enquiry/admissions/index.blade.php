@extends('layouts.app')

@section('title', 'BANSAL CLASS - Confirmed Admissions')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

@if(session('success'))
    <div id="successMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6 max-w-7xl mx-auto animate-fadeIn" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    </div>
@endif

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">

    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Confirmed Admissions</h1>
            <p class="text-gray-500">View and manage all confirmed student admissions</p>
        </div>
        <div class="flex gap-3 items-center">
            <a href="{{ route('enquiry.admissions.create') }}"
               class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Admission
            </a>
            <div class="bg-blue-600 p-4 rounded-xl shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">

        <div class="bg-blue-50 p-5 rounded-xl shadow-sm text-center">
            <p class="text-3xl font-bold text-blue-600">
                {{ $totalAdmissionsCount }}
            </p>
            <p class="text-sm text-gray-600">Total Admissions</p>
        </div>

        <div class="bg-green-50 p-5 rounded-xl shadow-sm text-center">
            <p class="text-3xl font-bold text-green-600">
                {{ $paidCount }}
            </p>
            <p class="text-sm text-gray-600">Paid (Cash/Online)</p>
        </div>

        <div class="bg-purple-50 p-5 rounded-xl shadow-sm text-center">
            <p class="text-3xl font-bold text-purple-600">
                {{ $installmentCount }}
            </p>
            <p class="text-sm text-gray-600">Installment Plans</p>
        </div>

        <div class="bg-orange-50 p-5 rounded-xl shadow-sm text-center">
            <p class="text-3xl font-bold text-orange-600">
                {{ $pendingInstallmentCount }}
            </p>
            <p class="text-sm text-gray-600">Pending Installments</p>
        </div>

    </div>


<!-- Student List Dropdown -->
<div id="studentListDropdown" class="hidden border border-gray-300 bg-white p-4 mb-6">
    <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
        <h3 class="text-base font-medium text-gray-900">Class <span id="dropdownClassName"></span> - Students</h3>
        <button onclick="closeStudentList()" class="text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <!-- Search Bar -->
    <div class="mb-3">
        <input type="text" id="studentSearch" placeholder="Search students..." 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               onkeyup="searchStudents()">
    </div>
    
    <!-- Action Buttons -->
    <!-- <div class="flex gap-2 mb-3">
        <button onclick="markPresent()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
            Mark Present
        </button>
        <button onclick="markAbsent()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Mark Absent
        </button>
        <button onclick="saveAttendance()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
            Save Attendance
        </button>
        <label class="flex items-center ml-auto">
            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" class="mr-2">
            <span class="text-sm font-medium text-gray-700">Select All</span>
        </label>
    </div> -->

    <div class="overflow-x-auto">
        <div id="studentList" class="space-y-2">
            <!-- Students will be loaded here -->
        </div>
    </div>
</div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" id="filterForm">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search Input -->
                <div class="flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by student name, roll number or class..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Class Filter -->
                <div class="sm:w-48">
                    <select name="class_filter"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ request('class_filter') == $class ? 'selected' : '' }}>
                                {{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fee Status Filter -->
                <div class="sm:w-52">
                    <select name="fee_status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Fee Status</option>
                        <option value="paid" {{ request('fee_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('fee_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="installment" {{ request('fee_status') == 'installment' ? 'selected' : '' }}>Installment</option>
                        <option value="pending_installment" {{ request('fee_status') == 'pending_installment' ? 'selected' : '' }}>Pending Installment</option>
                    </select>
                </div>

                <!-- Reset Button -->
                @if(request('search') || request('class_filter') || request('fee_status'))
                    <div>
                        <a href="{{ route('enquiry.admissions.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition h-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>


    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Roll No.</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Admission Date</th>
                    <th>Fee Status</th>
                    <th>Fee Structure</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admissions as $admission)
                <tr>
                    <td class="font-mono text-xs text-gray-500">
                        {{ $admission->roll_number }}
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="crm-avatar bg-gradient-to-br from-indigo-500 to-purple-600">
                                {{ substr($admission->student_name, 0, 1) }}
                            </div>
                            <span class="font-bold text-gray-900">{{ $admission->student_name }}</span>
                        </div>
                    </td>
                    <td>
                        {{ $admission->class }}
                    </td>
                    <td>
                        <span class="secondary-text">{{ \Carbon\Carbon::parse($admission->admission_date)->format('d M Y') }}</span>
                    </td>
                    <td>
                        @if($admission->payment_mode == 'installment')
                            @if($admission->fee_status == 'pending installment')
                                <span class="crm-badge crm-badge-warning">
                                    Pending Inst.
                                </span>
                            @else
                                <span class="crm-badge crm-badge-info">
                                    Installment
                                </span>
                            @endif
                        @elseif($admission->payment_mode == 'cash' || $admission->payment_mode == 'online')
                            <span class="crm-badge crm-badge-success">
                                Paid
                            </span>
                        @else
                            @if($admission->fee_status == 'paid')
                                <span class="crm-badge crm-badge-success">
                                    Paid
                                </span>
                            @elseif($admission->fee_status == 'pending')
                                <span class="crm-badge crm-badge-warning">
                                    Pending
                                </span>
                            @else
                                <span class="crm-badge crm-badge-danger">
                                    Overdue
                                </span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-900">₹{{ number_format($admission->paid_amount) }}</span>
                            <span class="secondary-text">/ ₹{{ number_format($admission->total_fee) }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('enquiry.admissions.show', $admission->id) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('enquiry.admissions.edit', $admission->id) }}"
                               class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="{{ route('enquiry.admissions.pdf', $admission->id) }}"
                               class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Download PDF">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg">No admissions found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $admissions->appends(request()->query())->links() }}
    </div>

    <!-- Class Cards Section -->
    <!-- <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Class-wise Student Selection</h2>
        <p class="text-gray-600">Click on any class to view students and manage attendance</p>
    </div>
    
    <div class="flex gap-3 mb-8 overflow-x-auto pb-2">
        @foreach($classesWithCounts as $classData)
        <div onclick="showClassStudents('{{ $classData->class }}')" 
             class="bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md flex-shrink-0 min-w-[120px]">
            <div class="text-center">
                <div class="text-lg font-bold text-gray-900 mb-1">{{ $classData->class }}</div>
                <div class="text-sm text-gray-600">{{ $classData->student_count }} Students</div>
            </div>
        </div>
        @endforeach
    </div>

</div> -->

<!-- Student List Dropdown -->
<!-- <div id="studentListDropdown" class="hidden border border-gray-300 bg-white p-4 mb-6">
    <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
        <h3 class="text-base font-medium text-gray-900">Class <span id="dropdownClassName"></span> - Students</h3>
        <button onclick="closeStudentList()" class="text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div> -->
    
    <!-- Search Bar -->
    <!-- <div class="mb-3">
        <input type="text" id="studentSearch" placeholder="Search students..." 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
               onkeyup="searchStudents()">
    </div> -->
    
    <!-- Action Buttons -->
    <!-- <div class="flex gap-2 mb-3">
        <label class="flex items-center">
            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" class="mr-2">
            <span class="text-sm font-medium text-gray-700">Select All</span>
        </label>
        <button onclick="markPresent()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
            Mark Present
        </button>
        <button onclick="markAbsent()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            Mark Absent
        </button>
        <button onclick="saveAttendance()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
            Save Attendance
        </button>
    </div> -->

</div>

<script>
// Hide success message after 10 seconds
setTimeout(function() {
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        successMessage.style.transition = 'opacity 0.5s ease';
        successMessage.style.opacity = '0';
        setTimeout(function() {
            successMessage.remove();
        }, 500);
    }
}, 10000);

// Class Students Dropdown Functions
let currentClassStudents = [];

function showClassStudents(className) {
    document.getElementById('dropdownClassName').textContent = className;
    document.getElementById('studentListDropdown').classList.remove('hidden');
    
    // Load students for this class
    fetch(`/enquiry/admissions/class/${className}/students`)
        .then(response => response.json())
        .then(data => {
            currentClassStudents = data.students;
            renderClassStudents();
        })
        .catch(error => {
            console.error('Error loading class students:', error);
            alert('Failed to load students. Please try again.');
        });
}

function closeStudentList() {
    document.getElementById('studentListDropdown').classList.add('hidden');
}

function renderClassStudents() {
    const studentList = document.getElementById('studentList');
    studentList.innerHTML = '';
    
    if (currentClassStudents.length === 0) {
        studentList.innerHTML = '<div class="text-center p-4 text-gray-500">No students found in this class</div>';
        return;
    }
    
    currentClassStudents.forEach(student => {
        const studentDiv = document.createElement('div');
        studentDiv.className = 'flex items-center p-2 border border-gray-200 rounded student-item';
        studentDiv.dataset.studentName = student.student_name;
        studentDiv.innerHTML = `
            <input type="checkbox" name="student_${student.id}" value="${student.id}" class="mr-3 student-checkbox" data-student-id="${student.id}" data-student-name="${student.student_name}">
            <span class="text-sm text-gray-900">${student.student_name}</span>
        `;
        studentList.appendChild(studentDiv);
    });
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    
    studentCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function markPresent() {
    const selectedStudents = getSelectedStudents();
    if (selectedStudents.length === 0) {
        alert('Please select at least one student.');
        return;
    }
    
    // Mark selected students as present in checkboxes
    selectedStudents.forEach(student => {
        const checkbox = document.querySelector(`[data-student-id="${student.id}"]`);
        if (checkbox) checkbox.checked = true;
    });
    
    // Auto-save attendance for marked students
    saveAttendanceForSelected('present');
}

function markAbsent() {
    const selectedStudents = getSelectedStudents();
    if (selectedStudents.length === 0) {
        alert('Please select at least one student.');
        return;
    }
    
    // Mark selected students as absent in checkboxes
    selectedStudents.forEach(student => {
        const checkbox = document.querySelector(`[data-student-id="${student.id}"]`);
        if (checkbox) checkbox.checked = false;
    });
    
    // Auto-save attendance for marked students
    saveAttendanceForSelected('absent');
}

function saveAttendanceForSelected(status) {
    const attendanceData = [];
    const currentClass = document.getElementById('dropdownClassName').textContent;
    
    // Get all students in current class
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        const studentId = checkbox.dataset.studentId;
        const isPresent = status === 'present' ? checkbox.checked : false;
        
        attendanceData.push({
            student_id: parseInt(studentId),
            attendance: isPresent ? 'present' : 'absent'
        });
    });
    
    if (attendanceData.length === 0) {
        alert('No students to save attendance for.');
        return;
    }
    
    fetch('/enquiry/admissions/save-attendance', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            class_name: currentClass,
            attendance: attendanceData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert('Failed to save attendance. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error saving attendance:', error);
        alert('Failed to save attendance. Please try again.');
    });
}

function getSelectedStudents() {
    const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
    return Array.from(selectedCheckboxes).map(checkbox => ({
        id: checkbox.dataset.studentId,
        name: checkbox.dataset.studentName
    }));
}

function searchStudents() {
    const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
    const studentList = document.getElementById('studentList');
    const students = studentList.querySelectorAll('.student-item');
    
    students.forEach(student => {
        const studentName = student.dataset.studentName.toLowerCase();
        if (studentName.includes(searchTerm)) {
            student.style.display = 'flex';
        } else {
            student.style.display = 'none';
        }
    });
}

function saveAttendance() {
    const attendanceData = [];
    const currentMonth = document.getElementById('dropdownClassName').textContent;
    
    // Get all students in the current class (both checked and unchecked)
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        const studentId = checkbox.dataset.studentId;
        const isPresent = checkbox.checked;
        
        attendanceData.push({
            student_id: parseInt(studentId),
            attendance: isPresent ? 'present' : 'absent'
        });
    });
    
    if (attendanceData.length === 0) {
        alert('No students to save attendance for.');
        return;
    }
    
    // Show loading state
    const saveButton = event.target;
    const originalText = saveButton.textContent;
    saveButton.textContent = 'Saving...';
    saveButton.disabled = true;
    
    fetch('/enquiry/admissions/save-attendance', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            class_name: currentMonth,
            attendance: attendanceData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeStudentList();
        } else {
            alert('Failed to save attendance. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error saving attendance:', error);
        alert('Failed to save attendance. Please try again.');
    })
    .finally(() => {
        // Restore button state
        saveButton.textContent = originalText;
        saveButton.disabled = false;
    });
}
</script>

@endsection