@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="bg-blue-600 rounded-full text-white flex items-center justify-center w-16 h-16 text-xl font-bold">
                    {{ substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1) }}
                </div>
                <div class="ml-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ ucfirst($employee->full_name) }}</h2>
                    <p class="text-gray-600">{{ ucfirst($employee->designation) }}</p>
                    <p class="text-gray-600">{{ ucfirst($employee->department) }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Employee Code</label>
                            <p class="text-gray-900">#{{ $employee->employee_code }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $employee->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-900">{{ $employee->phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                            <p class="text-gray-900">{{ $employee->date_of_birth ? date('d M Y', strtotime($employee->date_of_birth)) : 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Gender</label>
                            <p class="text-gray-900">{{ ucfirst($employee->gender ?? 'Not specified') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Designation</label>
                            <p class="text-gray-900">{{ ucfirst($employee->designation) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Department</label>
                            <p class="text-gray-900">{{ ucfirst($employee->department) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Joining Date</label>
                            <p class="text-gray-900">{{ date('d M Y', strtotime($employee->joining_date)) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Employment Type</label>
                            <p class="text-gray-900">{{ ucfirst($employee->employment_type) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status</label>
                            <p class="text-gray-900">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $employee->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Information -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Salary Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Basic Salary</label>
                        <p class="text-gray-900">₹{{ number_format($employee->basic_salary, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Salary Type</label>
                        <p class="text-gray-900">{{ ucfirst($employee->salary_type) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Payment Method</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $employee->payment_method)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Bank Information (if applicable) -->
            @if($employee->payment_method === 'bank_transfer')
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bank Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Bank Name</label>
                            <p class="text-gray-900">{{ ucfirst($employee->bank_name) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Account Number</label>
                            <p class="text-gray-900">****{{ substr($employee->account_number, -4) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">IFSC Code</label>
                            <p class="text-gray-900">{{ $employee->IFSC_code }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Academic Assignments -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Academic Assignments</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Assigned Classes</label>
                        <p class="text-gray-900">{{ ucfirst($employee->assigned_classes ?? 'Not assigned') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Assigned Subjects</label>
                        <p class="text-gray-900">{{ ucfirst($employee->assigned_subjects ?? 'Not assigned') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
