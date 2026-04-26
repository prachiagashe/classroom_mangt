@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Employee Management</h1>
            <p class="text-gray-500">Track and manage employee salary payments and profiles</p>
        </div>
        <div class="flex gap-3 items-center">
            <a href="{{ route('employee.create') }}" 
               class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Employee
            </a>
            <div class="bg-blue-600 p-4 rounded-xl shadow-md ml-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $employees->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $employees->where('status', 'Active')->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">On Leave</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $employees->where('status', 'On Leave')->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Departments</p>
                    <p class="text-2xl font-bold text-purple-600">5</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('employee.index') }}" class="flex flex-col md:flex-row gap-4" id="filterForm">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search employees..." 
                           value="{{ request()->get('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="document.getElementById('filterForm').submit()">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <select name="department" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    onchange="document.getElementById('filterForm').submit()">
                <option value="">All Departments</option>
                <option value="teaching" {{ request()->get('department') == 'teaching' ? 'selected' : '' }}>Teaching</option>
                <option value="administration" {{ request()->get('department') == 'administration' ? 'selected' : '' }}>Administration</option>
                <option value="support" {{ request()->get('department') == 'support' ? 'selected' : '' }}>Support Staff</option>
                <option value="management" {{ request()->get('department') == 'management' ? 'selected' : '' }}>Management</option>
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    onchange="document.getElementById('filterForm').submit()">
                <option value="">All Status</option>
                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="on_leave" {{ request()->get('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Apply Filters
            </button>
        </form>
    </div>

    <!-- Employee Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">Employee</th>
                        <th class="text-left p-4 font-semibold text-gray-700">ID</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Department</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Contact</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Status</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Class</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Subject</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 cursor-pointer hover:text-blue-600 transition-colors" 
                                           onclick="openAttendanceCalendar(event, {{ $employee->id }})">
                                            {{ $employee->full_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $employee->email }}</p>

                                        @if($employee->designation === 'Teacher')
                                            <div class="mt-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                                <h4 class="text-sm font-semibold text-purple-700 mb-2">Academic Assignment</h4>
                                                @if($employee->primary_class)
                                                    <div class="mb-2">
                                                        <span class="text-xs text-gray-600">Primary Class:</span>
                                                        <span class="font-medium">{{ $employee->primary_class }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($employee->assigned_classes_array)
                                                    <div class="mb-2">
                                                        <span class="text-xs text-gray-600">Classes:</span>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($employee->assigned_classes_array as $class)
                                                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">{{ $class }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($employee->assigned_subjects_array)
                                                    <div>
                                                        <span class="text-xs text-gray-600">Subjects:</span>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($employee->assigned_subjects_array as $subject)
                                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $subject }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 font-mono text-sm">#{{ $employee->employee_code }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($employee->department == 'teaching') bg-blue-100 text-blue-700
                                    @elseif($employee->department == 'administration') bg-purple-100 text-purple-700
                                    @elseif($employee->department == 'support') bg-orange-100 text-orange-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($employee->department) }}
                                </span>
                            </td>
                            <td class="p-4">
                                <p class="text-sm">
                                    @if($employee->phone && preg_match('/^[0-9]{10}$/', $employee->phone))
                                        <span class="text-green-600">{{ $employee->phone }}</span>
                                    @elseif($employee->phone)
                                        <span class="text-red-600" title="Invalid phone number format">{{ $employee->phone }}</span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </p>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($employee->status == 'Active') bg-emerald-100 text-emerald-700
                                    @elseif($employee->status == 'Inactive') bg-rose-100 text-rose-700
                                    @else bg-amber-100 text-amber-700
                                    @endif">
                                    {{ $employee->status }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($employee->assigned_classes_array)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($employee->assigned_classes_array as $class)
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">{{ $class }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-xs font-semibold text-green-700">
                                {{ $employee->assigned_subjects }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('employee.show', $employee->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Edit Employee">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('employee.edit', $employee->id) }}" class="text-gray-600 hover:text-gray-800 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-lg font-medium">No employees found</p>
                                    <p class="text-sm mt-2">Get started by adding your first employee.</p>
                                    <a href="{{ route('employee.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Employee
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing 1 to 3 of 24 results
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 transition">
                    Previous
                </button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 transition">2</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 transition">3</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<x-modal id="addEmployeeModal" title="Add New Employee" :show="false" maxWidth="2xl">
    <form id="addEmployeeForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Employee Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="employeeName" required 
                           placeholder="John Doe"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="employeeEmail" required 
                           placeholder="john@example.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                    <input type="tel" id="employeePhone" required 
                           placeholder="+1 (555) 123-4567"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                    <select id="employeeDepartment" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Department</option>
                        <option value="teaching">Teaching</option>
                        <option value="admin">Administration</option>
                        <option value="support">Support Staff</option>
                        <option value="management">Management</option>
                    </select>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Position -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                    <input type="text" id="employeePosition" required 
                           placeholder="Senior Developer"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Join Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Join Date <span class="text-red-500">*</span></label>
                    <input type="date" id="employeeJoinDate" required 
                           max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="employeeStatus" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="on_leave">On Leave</option>
                    </select>
                </div>

                <!-- Employee Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee Type <span class="text-red-500">*</span></label>
                    <select id="employeeType" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Type</option>
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="intern">Intern</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea id="employeeAddress" rows="3" 
                      placeholder="123 Main St, City, State 12345"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6">
            <button type="button" onclick="closeModal('addEmployeeModal')" 
                    class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium order-2 sm:order-1">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium order-1 sm:order-2 shadow-lg">
                Add Employee
            </button>
        </div>
    </form>
</x-modal>

<script>
function openAddEmployeeModal() {
    openModal('addEmployeeModal');
}

function closeAddEmployeeModal() {
    closeModal('addEmployeeModal');
}

// Close modal when clicking outside
document.getElementById('addEmployeeModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeAddEmployeeModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddEmployeeModal();
        closeAttendanceCalendarModal();
    }
});

// --- Attendance Calendar Logic ---

let currentCalendarEmployeeId = null;
let currentCalendarMonth = new Date().getMonth() + 1;
let currentCalendarYear = new Date().getFullYear();

function openAttendanceCalendar(event, employeeId) {
    event.preventDefault();
    currentCalendarEmployeeId = employeeId;
    currentCalendarMonth = new Date().getMonth() + 1;
    currentCalendarYear = new Date().getFullYear();
    
    const modal = document.getElementById('attendanceCalendarModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    fetchAttendanceCalendar();
}

function closeAttendanceCalendarModal() {
    const modal = document.getElementById('attendanceCalendarModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scroll
    }
}

function changeCalendarMonth(offset) {
    currentCalendarMonth += offset;
    if (currentCalendarMonth > 12) {
        currentCalendarMonth = 1;
        currentCalendarYear++;
    } else if (currentCalendarMonth < 1) {
        currentCalendarMonth = 12;
        currentCalendarYear--;
    }
    fetchAttendanceCalendar();
}

async function fetchAttendanceCalendar() {
    const container = document.getElementById('calendarContainer');
    const title = document.getElementById('calendarMonthTitle');
    const summary = document.getElementById('calendarSummary');
    
    // Show Loader
    container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-20 w-full">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-500 font-medium">Fetching attendance records...</p>
        </div>
    `;
    
    try {
        let url = "{{ route('employee.attendance-calendar', ['employee' => ':id']) }}";
        url = url.replace(':id', currentCalendarEmployeeId);
        const response = await fetch(`${url}?month=${currentCalendarMonth}&year=${currentCalendarYear}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('employeeCalendarName').textContent = data.employee;
            
            // Update Title
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            title.textContent = `${monthNames[data.month - 1]} ${data.year}`;
            
            // Update Summary Counts
            summary.innerHTML = `
                <div class="bg-emerald-50/50 border border-emerald-100 p-2.5 rounded-xl text-center">
                    <p class="text-[8px] text-emerald-600 font-black uppercase tracking-tighter mb-0.5">Present</p>
                    <p class="text-xs font-black text-emerald-700">${data.counts.present}</p>
                </div>
                <div class="bg-rose-50/50 border border-rose-100 p-2.5 rounded-xl text-center">
                    <p class="text-[8px] text-rose-600 font-black uppercase tracking-tighter mb-0.5">Absent</p>
                    <p class="text-xs font-black text-rose-700">${data.counts.absent}</p>
                </div>
                <div class="bg-amber-50/50 border border-amber-100 p-2.5 rounded-xl text-center">
                    <p class="text-[8px] text-amber-600 font-black uppercase tracking-tighter mb-0.5">Leaves</p>
                    <p class="text-xs font-black text-amber-700">${data.counts.leave}</p>
                </div>
                <div class="bg-blue-50/50 border border-blue-100 p-2.5 rounded-xl text-center">
                    <p class="text-[8px] text-blue-600 font-black uppercase tracking-tighter mb-0.5">Holidays</p>
                    <p class="text-xs font-black text-blue-700">${data.counts.holiday}</p>
                </div>
            `;
            
            renderCalendar(data.calendar, data.month, data.year);
        } else {
            const container = document.getElementById('calendarContainer');
            container.innerHTML = `<p class="text-red-500 p-4 text-center text-xs font-medium">${data.message}</p>`;
        }
    } catch (error) {
        console.error('Error:', error);
        const container = document.getElementById('calendarContainer');
        container.innerHTML = `<p class="text-red-500 p-4 text-center text-xs font-medium">Failed to load calendar.</p>`;
    }
}
function renderCalendar(calendarData, month, year) {
    const container = document.getElementById('calendarContainer');
    const firstDay = new Date(year, month - 1, 1).getDay();
    const daysInMonth = new Date(year, month, 0).getDate();

    const hasData = Object.values(calendarData).some(d => d.status !== 'none');
    
    if (!hasData) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 w-full text-center">
                <svg class="w-10 h-10 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-xs text-slate-400 font-medium px-6">No attendance data available for this month.</p>
            </div>
        `;
        return;
    }

    let html = `
        <div class="grid grid-cols-7 gap-1">
            ${['S', 'M', 'T', 'W', 'T', 'F', 'S'].map(day => 
                `<div class="text-center text-[9px] font-bold text-slate-400 py-1 uppercase">${day}</div>`
            ).join('')}
    `;
    
    for (let i = 0; i < firstDay; i++) {
        html += `<div class="aspect-square"></div>`;
    }
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayData = calendarData[dateStr] || { status: 'none', title: '' };
        
        let statusClasses = 'bg-white text-slate-400 border-slate-50';
        let statusDot = '';
        
        if (dayData.status === 'present') {
            statusClasses = 'bg-emerald-50 text-emerald-700 border-emerald-100 font-bold';
            statusDot = '<div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1 shadow-sm shadow-emerald-200"></div>';
        } else if (dayData.status === 'absent') {
            statusClasses = 'bg-rose-50 text-rose-700 border-rose-100 font-bold';
            statusDot = '<div class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1 shadow-sm shadow-rose-200"></div>';
        } else if (dayData.status === 'leave') {
            statusClasses = 'bg-amber-50 text-amber-700 border-amber-100 font-bold';
            statusDot = '<div class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-1 shadow-sm shadow-amber-200"></div>';
        } else if (dayData.status === 'holiday') {
            statusClasses = 'bg-blue-50 text-blue-700 border-blue-100 font-bold';
            statusDot = '<div class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1 shadow-sm shadow-blue-200"></div>';
        }
        
        html += `
            <div class="aspect-square border ${statusClasses} rounded-xl flex flex-col justify-center items-center group relative transition-all hover:scale-105 hover:shadow-sm cursor-default" 
                 title="${dayData.title}">
                <span class="text-xs">${day}</span>
                ${statusDot}
            </div>
        `;
    }
    
    html += `</div>`;
    container.innerHTML = html;
}
// Close on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAttendanceCalendarModal();
    }
});
</script>

<!-- Attendance Calendar Modal (Square PTM Style) -->
<div id="attendanceCalendarModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <!-- Premium Backdrop Overlay -->
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeAttendanceCalendarModal()"></div>
    
    <!-- Modal Container (Compact Square) -->
    <div id="attendanceCalendarPopup" class="relative w-[500px] bg-white rounded-[24px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 flex flex-col">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white flex-shrink-0">
            <div>
                <h2 id="employeeCalendarName" class="text-lg font-bold text-slate-800">Attendance Calendar</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Monthly Insights</p>
            </div>
            <button onclick="closeAttendanceCalendarModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-50 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-8 flex-1 overflow-y-auto custom-scrollbar">
            <!-- Month Navigation -->
            <div class="flex items-center justify-between mb-6 bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                <button onclick="changeCalendarMonth(-1)" class="p-2 hover:bg-white hover:shadow-sm rounded-lg transition-all text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h3 id="calendarMonthTitle" class="text-xs font-bold text-slate-700 uppercase tracking-widest">April 2026</h3>
                <button onclick="changeCalendarMonth(1)" class="p-2 hover:bg-white hover:shadow-sm rounded-lg transition-all text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Summary Counts Row -->
            <div id="calendarSummary" class="grid grid-cols-4 gap-3 mb-6">
                <!-- Data injected here -->
            </div>

            <div id="calendarContainer" class="bg-slate-50 rounded-2xl p-4 border border-slate-100 min-h-[350px]">
                <!-- Calendar grid injected here -->
            </div>

            <!-- Legend -->
            <div class="mt-6 flex justify-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Absent</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Leave</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Holiday</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 flex-shrink-0">
            <button onclick="closeAttendanceCalendarModal()" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all shadow-sm">
                Cancel
            </button>
            <button onclick="closeAttendanceCalendarModal()" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-xs font-bold hover:shadow-lg hover:shadow-blue-200 transition-all">
                Close
            </button>
        </div>
    </div>
</div>


<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>
@endsection