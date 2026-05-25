@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Header with Breadcrumb -->
        <div class="mb-8">
            <!-- Breadcrumb with Back Button -->
            <div class="flex items-center justify-between mb-4">
                <nav class="flex items-center space-x-2 text-sm text-gray-500">
                    <a href="{{ route('employee.index') }}" class="hover:text-gray-700 transition">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('employee.index') }}" class="hover:text-gray-700 transition">Employee Management</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-900 font-medium">Edit Employee</span>
                </nav>
                
                <!-- Back Button -->
                <a href="{{ route('employee.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Employees
                </a>
            </div>
            
            <!-- Page Title -->
            <h1 class="text-3xl font-bold text-gray-900">Edit Employee</h1>
            <p class="text-gray-600 mt-2">Update employee information below</p>
        </div>

        <!-- Validation Errors Alert -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Please fix the errors below.</h3>
                        <ul class="mt-2 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="employeeForm" method="POST" action="{{ route('employee.update', $employee->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <!-- Personal Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" required
                               class="w-full px-4 py-2.5 border @error('first_name') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="John" value="{{ old('first_name', $employee->first_name) }}">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name"
                               class="w-full px-4 py-2.5 border @error('middle_name') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Michael" value="{{ old('middle_name', $employee->middle_name) }}">
                        @error('middle_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" required
                               class="w-full px-4 py-2.5 border @error('last_name') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Doe" value="{{ old('last_name', $employee->last_name) }}">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required
                               class="w-full px-4 py-2.5 border @error('email') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="john.doe@company.com" value="{{ old('email', $employee->email) }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" required
                               class="w-full px-4 py-2.5 border @error('phone') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="9876543210" value="{{ old('phone', $employee->phone) }}">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth"
                               class="w-full px-4 py-2.5 border @error('date_of_birth') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               value="{{ old('date_of_birth', $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('Y-m-d') : '') }}">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-2.5 border @error('gender') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Professional Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                        <input type="text" readonly value="{{ $employee->employee_code }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                        <select name="designation" required class="w-full px-4 py-2.5 border @error('designation') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Select Designation</option>
                            <option value="teacher" {{ old('designation', $employee->designation) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="counselor" {{ old('designation', $employee->designation) == 'counselor' ? 'selected' : '' }}>Counselor</option>
                            <option value="admin" {{ old('designation', $employee->designation) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="accountant" {{ old('designation', $employee->designation) == 'accountant' ? 'selected' : '' }}>Accountant</option>
                        </select>
                        @error('designation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select name="department" required class="w-full px-4 py-2.5 border @error('department') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Select Department</option>
                            <option value="teaching" {{ old('department', $employee->department) == 'teaching' ? 'selected' : '' }}>Teaching</option>
                            <option value="administration" {{ old('department', $employee->department) == 'administration' ? 'selected' : '' }}>Administration</option>
                            <option value="support" {{ old('department', $employee->department) == 'support' ? 'selected' : '' }}>Support Staff</option>
                            <option value="management" {{ old('department', $employee->department) == 'management' ? 'selected' : '' }}>Management</option>
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Joining Date</label>
                        <input type="date" name="joining_date" required
                               class="w-full px-4 py-2.5 border @error('joining_date') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               value="{{ old('joining_date', $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('Y-m-d') : '') }}">
                        @error('joining_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type</label>
                        <select name="employment_type" required class="w-full px-4 py-2.5 border @error('employment_type') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Select Type</option>
                            <option value="full-time" {{ old('employment_type', $employee->employment_type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="part-time" {{ old('employment_type', $employee->employment_type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="contract" {{ old('employment_type', $employee->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                        </select>
                        @error('employment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="Active" {{ (old('status', $employee->status) == 'Active' || old('status', $employee->status) == null) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Salary Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Salary Details</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary</label>
                        <input type="number" name="basic_salary" required
                               class="w-full px-4 py-2.5 border @error('basic_salary') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="45000" value="{{ old('basic_salary', $employee->basic_salary) }}">
                        @error('basic_salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary Type</label>
                        <select name="salary_type" required class="w-full px-4 py-2.5 border @error('salary_type') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Select Type</option>
                            <option value="monthly" {{ old('salary_type', $employee->salary_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="hourly" {{ old('salary_type', $employee->salary_type) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="yearly" {{ old('salary_type', $employee->salary_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        @error('salary_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <input type="text" readonly value="Bank Transfer"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed font-medium transition-all">
                        <input type="hidden" name="payment_method" value="bank_transfer">
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name"
                               class="w-full px-4 py-2.5 border @error('bank_name') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="State Bank of India" value="{{ old('bank_name', $employee->bank_name) }}">
                        @error('bank_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="account_number"
                               class="w-full px-4 py-2.5 border @error('account_number') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="1234567890" value="{{ old('account_number', $employee->account_number) }}">
                        @error('account_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                        <input type="text" name="IFSC_code"
                               class="w-full px-4 py-2.5 border @error('IFSC_code') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="SBIN0001234" value="{{ old('IFSC_code', $employee->IFSC_code) }}">
                        @error('IFSC_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

         
            <!-- Education Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c14.754 0 16.5-1.253"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Education Details</h2>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Education Information</label>
                    <textarea name="education" rows="6"
                              class="w-full px-4 py-2.5 border @error('education') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="Enter education details...">{{ old('education', $employee->education) }}</textarea>
                    @error('education')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Experience Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900">Experience Details</h2>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Experience Information</label>
                    <textarea name="experience" rows="6"
                              class="w-full px-4 py-2.5 border @error('experience') border-red-500 @enderror border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="Enter experience details...">{{ old('experience', $employee->experience) }}</textarea>
                    @error('experience')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


               <!-- Academic Assignment Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C12 2.253 4 2.253 6 4.253v13c0 5.747-2.253 8-8 8v13c0 5.747-2.253 8 8 8v13c0 5.747-2.253 8-8-8z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Academic Assignment</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Assigned Classes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Assigned Classes</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                                $classesList = ['5th','6th','7th','8th','9th','10th','11th','12th'];
                                $oldClasses = old('assigned_classes', $employee->assigned_classes);
                                $selectedClasses = is_array($oldClasses) ? $oldClasses : ($oldClasses ? explode(', ', $oldClasses) : []);
                            @endphp
                            
                            @foreach($classesList as $class)
                                <div class="class-card flex items-center justify-center gap-2 p-3 rounded-xl border border-gray-300 bg-white text-gray-600 font-bold text-sm transition-all duration-200 cursor-pointer hover:bg-gray-50"
                                     data-class="{{$class}}"
                                     onclick="toggleAndLoadClass('{{$class}}')">
                                    <svg class="w-4 h-4 hidden check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{$class}} Class</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Hidden Checkboxes for Classes Submission -->
                        <div class="hidden" id="hiddenClassesContainer">
                            @foreach($classesList as $class)
                                <input type="checkbox" id="hidden_class_{{str_replace(' ', '_', $class)}}" name="assigned_classes[]" value="{{$class}}" @if(in_array($class, $selectedClasses)) checked @endif>
                            @endforeach
                        </div>
                        @error('assigned_classes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">Select all classes this teacher will handle</p>
                    </div>
                    
                    <!-- Assigned Subjects -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-700">Assigned Subjects</label>
                            <span id="activeClassLabel" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded hidden"></span>
                        </div>
                        
                        <div id="subjectsLoading" class="hidden text-center py-4 text-gray-400">
                            <svg class="animate-spin h-5 w-5 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs">Loading subjects...</span>
                        </div>
                        
                        <div id="subjectsContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="col-span-full text-center py-6 text-sm text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                                Select a class to view subjects
                            </div>
                        </div>
                        
                        <!-- Single Hidden Input for Subjects JSON -->
                        <input type="hidden" name="assigned_subjects" id="assigned_subjects_input" value="{{ old('assigned_subjects', $employee->assigned_subjects) }}">
                        
                        @error('assigned_subjects')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">Select all subjects this teacher will handle</p>
                    </div>
                </div>
                
                <!-- Mapping Preview Section -->
                <div class="mt-8 border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">Teaching Assignments Preview</h3>
                    <div id="mappingPreviewContainer" class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="text-sm text-gray-500 italic text-center">No assignments selected yet.</div>
                    </div>
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('employee.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium shadow-sm">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedClasses = [];
let classSubjectMap = {};
let activeClass = null;

// Initialize mapping from old input if exists
try {
    const oldSubjectsJson = document.getElementById('assigned_subjects_input').value;
    if (oldSubjectsJson && oldSubjectsJson.trim() !== '') {
        classSubjectMap = JSON.parse(oldSubjectsJson);
    }
} catch (e) {
    console.error("Error parsing old assigned_subjects JSON", e);
}

function updateMappingInput() {
    document.getElementById('assigned_subjects_input').value = JSON.stringify(classSubjectMap);
    renderMappingPreview();
}

function renderMappingPreview() {
    const container = document.getElementById('mappingPreviewContainer');
    let hasSelections = false;
    let html = '<div class="space-y-3">';
    
    for (const [cls, subjects] of Object.entries(classSubjectMap)) {
        if (subjects && subjects.length > 0) {
            hasSelections = true;
            html += `
                <div class="flex items-start gap-3 p-3 bg-white border border-gray-100 rounded-lg shadow-sm">
                    <div class="font-bold text-indigo-700 min-w-[80px]">${cls}</div>
                    <div class="text-gray-400 mt-1">→</div>
                    <div class="flex flex-wrap gap-2 flex-1">
                        ${subjects.map(sub => `<span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-gray-200">${sub}</span>`).join('')}
                    </div>
                </div>
            `;
        }
    }
    
    html += '</div>';
    
    if (!hasSelections) {
        container.innerHTML = '<div class="text-sm text-gray-500 italic text-center py-4">No assignments selected yet.</div>';
    } else {
        container.innerHTML = html;
    }
}

function updateClassUI(className, isSelected, isActive) {
    const cards = document.querySelectorAll('.class-card');
    cards.forEach(card => {
        if (card.dataset.class === className) {
            const icon = card.querySelector('.check-icon');
            
            // Selection state
            if (isSelected) {
                card.classList.remove('bg-white', 'border-gray-300', 'text-gray-600');
                if (isActive) {
                    card.classList.add('bg-indigo-100', 'border-indigo-500', 'text-indigo-700', 'shadow-sm', 'ring-2', 'ring-indigo-200');
                } else {
                    card.classList.remove('bg-indigo-100', 'border-indigo-500', 'text-indigo-700', 'ring-2', 'ring-indigo-200');
                    card.classList.add('bg-green-100', 'border-green-500', 'text-green-700', 'shadow-sm');
                }
                if (icon) icon.classList.remove('hidden');
            } else {
                card.classList.remove('bg-green-100', 'border-green-500', 'text-green-700', 'shadow-sm', 'bg-indigo-100', 'border-indigo-500', 'text-indigo-700', 'ring-2', 'ring-indigo-200');
                card.classList.add('bg-white', 'border-gray-300', 'text-gray-600');
                if (icon) icon.classList.add('hidden');
            }
        } else {
            // Remove active rings from other cards
            card.classList.remove('ring-2', 'ring-indigo-200');
            // Reapply normal selected colors if they were active
            if (selectedClasses.includes(card.dataset.class)) {
                card.classList.remove('bg-indigo-100', 'border-indigo-500', 'text-indigo-700');
                card.classList.add('bg-green-100', 'border-green-500', 'text-green-700');
            }
        }
    });
}

function toggleAndLoadClass(className) {
    const hiddenCheckboxId = 'hidden_class_' + className.replace(/\s+/g, '_');
    const hiddenCheckbox = document.getElementById(hiddenCheckboxId);
    
    const index = selectedClasses.indexOf(className);
    
    if (index !== -1 && activeClass === className) {
        // Unselect if clicking the active one again
        selectedClasses.splice(index, 1);
        if (hiddenCheckbox) hiddenCheckbox.checked = false;
        delete classSubjectMap[className];
        updateMappingInput();
        
        activeClass = null;
        updateClassUI(className, false, false);
        
        // Clear subjects
        document.getElementById('activeClassLabel').classList.add('hidden');
        document.getElementById('subjectsContainer').innerHTML = `<div class="col-span-full text-center py-6 text-sm text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">Select a class to view subjects</div>`;
    } else {
        // Select it and make it active
        if (index === -1) {
            selectedClasses.push(className);
            if (hiddenCheckbox) hiddenCheckbox.checked = true;
            if (!classSubjectMap[className]) {
                classSubjectMap[className] = [];
            }
        }
        
        activeClass = className;
        
        // Update UI for all classes to reflect the new active one
        selectedClasses.forEach(cls => {
            updateClassUI(cls, true, cls === activeClass);
        });
        
        loadSubjectsForClass(className);
    }
}

function loadSubjectsForClass(className) {
    document.getElementById('subjectsContainer').innerHTML = '';
    document.getElementById('subjectsLoading').classList.remove('hidden');
    document.getElementById('activeClassLabel').textContent = className + ' Subjects';
    document.getElementById('activeClassLabel').classList.remove('hidden');
    
    fetch(`/api/subjects/${className}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('subjectsLoading').classList.add('hidden');
            const container = document.getElementById('subjectsContainer');
            
            if (!data || data.length === 0) {
                container.innerHTML = `<div class="col-span-full text-center py-6 text-sm text-red-400 border-2 border-dashed border-red-100 rounded-xl bg-red-50">No subjects found for ${className}</div>`;
                return;
            }
            
            const selectedForClass = classSubjectMap[className] || [];
            
            data.forEach(sub => {
                const isSelected = selectedForClass.includes(sub.name);
                const bgClass = isSelected ? 'bg-green-100 border-green-500 text-green-700 shadow-sm' : 'bg-white border-gray-300 text-gray-600';
                const iconHidden = isSelected ? '' : 'hidden';
                
                const card = document.createElement('div');
                card.className = `subject-card flex items-center justify-center gap-2 p-3 rounded-xl border font-bold text-sm transition-all duration-200 cursor-pointer hover:bg-gray-50 ${bgClass}`;
                card.onclick = () => toggleSubject(card, className, sub.name);
                
                card.innerHTML = `
                    <svg class="w-4 h-4 ${iconHidden} check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <span>${sub.name}</span>
                `;
                container.appendChild(card);
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById('subjectsLoading').classList.add('hidden');
            document.getElementById('subjectsContainer').innerHTML = `<div class="col-span-full text-center py-6 text-sm text-red-400 border-2 border-dashed border-red-100 rounded-xl bg-red-50">Error loading subjects</div>`;
        });
}

function toggleSubject(cardElement, className, subjectName) {
    if (!classSubjectMap[className]) {
        classSubjectMap[className] = [];
    }
    
    const arr = classSubjectMap[className];
    const index = arr.indexOf(subjectName);
    
    if (index !== -1) {
        // Deselect
        arr.splice(index, 1);
        cardElement.classList.remove('bg-green-100', 'border-green-500', 'text-green-700', 'shadow-sm');
        cardElement.classList.add('bg-white', 'border-gray-300', 'text-gray-600');
        const icon = cardElement.querySelector('.check-icon');
        if(icon) icon.classList.add('hidden');
    } else {
        // Select
        arr.push(subjectName);
        cardElement.classList.remove('bg-white', 'border-gray-300', 'text-gray-600');
        cardElement.classList.add('bg-green-100', 'border-green-500', 'text-green-700', 'shadow-sm');
        const icon = cardElement.querySelector('.check-icon');
        if(icon) icon.classList.remove('hidden');
    }
    
    updateMappingInput();
}

function initSelections() {
    // Initialize selected classes
    document.querySelectorAll('input[id^="hidden_class_"]').forEach(cb => {
        if (cb.checked) {
            selectedClasses.push(cb.value);
            updateClassUI(cb.value, true, false);
            
            if (!classSubjectMap[cb.value]) {
                classSubjectMap[cb.value] = [];
            }
        }
    });
    
    // Select the first class by default if any exist
    if (selectedClasses.length > 0) {
        activeClass = selectedClasses[0];
        updateClassUI(activeClass, true, true);
        loadSubjectsForClass(activeClass);
    }
    
    updateMappingInput();
}

// Disable all form fields when validation errors are present
document.addEventListener('DOMContentLoaded', function() {
    initSelections();
    @if ($errors->any())
        // Disable all input fields
        const form = document.getElementById('employeeForm');
        const allInputs = form.querySelectorAll('input, select, textarea, button');
        
        allInputs.forEach(function(element) {
            element.disabled = true;
        });
        
        // Enable only Cancel button and navigation links
        const cancelButton = form.querySelector('a[href*="employee.index"]');
        if (cancelButton) {
            cancelButton.disabled = false;
        }
        
        // Add visual indication that form is locked
        form.style.opacity = '0.7';
        form.style.pointerEvents = 'none';
        
        // Show a message that form is locked
        const lockMessage = document.createElement('div');
        lockMessage.className = 'fixed top-4 right-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg shadow-lg z-50';
        lockMessage.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="font-medium">Form Locked</span>
            </div>
            <p class="text-sm mt-1">Please fix validation errors to continue</p>
        `;
        document.body.appendChild(lockMessage);
        
        // Auto-remove lock message after 5 seconds
        setTimeout(function() {
            if (lockMessage.parentNode) {
                lockMessage.parentNode.removeChild(lockMessage);
            }
        }, 5000);
    @endif
});
</script>
@endsection
