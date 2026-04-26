@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="p-6">
    <!-- Header with Title and Action Buttons -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Employee Details</h1>
            </div>
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('employee.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                    Back to List
                </a>
                <a href="{{ route('employee.edit', $employee->id) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    Edit Employee
                </a>
            </div>
        </div>
    </div>

    <!-- Personal Information Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Personal Information</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- Full Name -->
            <div>
                <p class="text-gray-600 text-sm">Full Name</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->full_name }}</p>
            </div>

            <!-- Email -->
            <div>
                <p class="text-gray-600 text-sm">Email</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->email }}</p>
            </div>

            <!-- Phone -->
            <div>
                <p class="text-gray-600 text-sm">Phone</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->phone ?? 'N/A' }}</p>
            </div>

            <!-- Date of Birth -->
            <div>
                <p class="text-gray-600 text-sm">Date of Birth</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->date_of_birth ? $employee->date_of_birth->format('d M, Y') : 'N/A' }}</p>
            </div>

            <!-- Gender -->
            <div>
                <p class="text-gray-600 text-sm">Gender</p>
                <p class="text-gray-900 font-semibold mt-1 capitalize">{{ $employee->gender ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Professional Information Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Professional Information</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- Employee Code -->
            <div>
                <p class="text-gray-600 text-sm">Employee Code</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->employee_code }}</p>
            </div>

            <!-- Designation -->
            <div>
                <p class="text-gray-600 text-sm">Designation</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->designation }}</p>
            </div>

            <!-- Department -->
            <div>
                <p class="text-gray-600 text-sm">Department</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->department }}</p>
            </div>

            <!-- Joining Date -->
            <div>
                <p class="text-gray-600 text-sm">Joining Date</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->joining_date->format('d M, Y') }}</p>
            </div>

            <!-- Employment Type -->
            <div>
                <p class="text-gray-600 text-sm">Employment Type</p>
                <p class="text-gray-900 font-semibold mt-1 capitalize">{{ $employee->employment_type }}</p>
            </div>

            <!-- Status -->
            <div>
                <p class="text-gray-600 text-sm">Status</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->status }}</p>
            </div>
        </div>
    </div>

    <!-- Salary Details Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Salary Details</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- Basic Salary -->
            <div>
                <p class="text-gray-600 text-sm">Basic Salary</p>
                <p class="text-gray-900 font-semibold mt-1">₹{{ number_format($employee->basic_salary, 2) }}</p>
            </div>

            <!-- Salary Type -->
            <div>
                <p class="text-gray-600 text-sm">Salary Type</p>
                <p class="text-gray-900 font-semibold mt-1 capitalize">{{ $employee->salary_type }}</p>
            </div>

            <!-- Payment Method -->
            <div>
                <p class="text-gray-600 text-sm">Payment Method</p>
                <p class="text-gray-900 font-semibold mt-1 capitalize">
                    @if($employee->payment_method === 'bank_transfer')
                        Bank Transfer
                    @elseif($employee->payment_method === 'cash')
                        Cash
                    @elseif($employee->payment_method === 'upi')
                        UPI
                    @else
                        {{ $employee->payment_method }}
                    @endif
                </p>
            </div>

            <!-- Bank Name -->
            @if($employee->bank_name)
            <div>
                <p class="text-gray-600 text-sm">Bank Name</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->bank_name }}</p>
            </div>
            @endif

            <!-- Account Number -->
            @if($employee->account_number)
            <div>
                <p class="text-gray-600 text-sm">Account Number</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->account_number }}</p>
            </div>
            @endif

            <!-- IFSC Code -->
            @if($employee->IFSC_code)
            <div>
                <p class="text-gray-600 text-sm">IFSC Code</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->IFSC_code }}</p>
            </div>
            @endif
        </div>
    </div>


    <!-- Academic Assignment Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Academic Assignment</h2>

        <div class="grid grid-cols-2 gap-6">
            <!-- Assigned Classes -->
            <div>
                <p class="text-gray-600 text-sm">Assigned Classes</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->assigned_classes ?? 'N/A' }}</p>
            </div>

            <!-- Assigned Subjects -->
            <div>
                <p class="text-gray-600 text-sm">Assigned Subjects</p>
                <p class="text-gray-900 font-semibold mt-1">{{ $employee->assigned_subjects ?? 'N/A' }}</p>
            </div>
        </div>
    </div>


    <!-- Education Details Section -->
    @if($employee->education)
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Education Details</h2>

        <div>
            <p class="text-gray-600 text-sm">Education</p>
            <p class="text-gray-900 font-semibold mt-1">{{ $employee->education }}</p>
        </div>
    </div>
    @endif


    <!-- Experience Details Section -->
    @if($employee->experience)
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Experience Details</h2>

        <div>
            <p class="text-gray-600 text-sm">Experience</p>
            <p class="text-gray-900 font-semibold mt-1">{{ $employee->experience }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
