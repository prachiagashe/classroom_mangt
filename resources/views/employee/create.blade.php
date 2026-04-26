@extends('layouts.app')

@section('title', 'Add New Employee')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-[1600px] mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('employee.index') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('employee.index') }}" class="hover:text-indigo-600 transition">Employees</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-900 font-medium">Add New</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Add New Employee</h1>
                <p class="text-gray-500 mt-1">Configure professional and personal details for onboarding.</p>
            </div>
            
            <a href="{{ route('employee.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 hover:text-gray-900 border border-gray-200 rounded-xl shadow-sm hover:shadow transition-all font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to List
            </a>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 rounded-2xl p-5 mb-8 flex gap-4 animate-shake">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-red-800">Form Submission Failed</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="employeeForm" method="POST" action="{{ route('employee.store') }}" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                <!-- Left Column: Primary Details -->
                <div class="xl:col-span-7 space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                                <p class="text-sm text-gray-500">Legal name and contact details</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" required maxlength="50" value="{{ old('first_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                       placeholder="John">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Middle Name</label>
                                <input type="text" name="middle_name" maxlength="50" value="{{ old('middle_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                       placeholder="M.">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" required maxlength="50" value="{{ old('last_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                       placeholder="Doe">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                       placeholder="john.doe@example.com">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Phone <span class="text-red-500">*</span></label>
                                <input type="text" id="phone" name="phone" required value="{{ old('phone') }}" 
                                       maxlength="10" 
                                       oninput="restrictPhone(this)" 
                                       onblur="checkPhone(this)"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder:text-gray-300"
                                       placeholder="9876543210">
                                <p id="phoneError" class="hidden text-[10px] text-red-500 mt-1 font-bold italic uppercase tracking-tighter transition-all">Must be 10 digits starting with 7, 8, or 9</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">DOB <span class="text-red-500">*</span></label>
                                <input type="date" id="date_of_birth" name="date_of_birth" required value="{{ old('date_of_birth') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all">
                                <p id="dobError" class="hidden text-[10px] text-red-500 mt-1 font-bold italic uppercase tracking-tighter">Under 18 years not allowed</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all bg-white">
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Professional Information</h2>
                                <p class="text-sm text-gray-500">Employment details and positioning</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Designation <span class="text-red-500">*</span></label>
                                <select name="designation" id="designation" required onchange="toggleAcademicAssignment()"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all bg-white">
                                    <option value="">Select</option>
                                    <option value="teacher" {{ old('designation') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="counselor" {{ old('designation') == 'counselor' ? 'selected' : '' }}>Counselor</option>
                                    <option value="admin" {{ old('designation') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="accountant" {{ old('designation') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Department <span class="text-red-500">*</span></label>
                                <select name="department" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all bg-white">
                                    <option value="">Select</option>
                                    <option value="teaching" {{ old('department') == 'teaching' ? 'selected' : '' }}>Teaching</option>
                                    <option value="administration" {{ old('department') == 'administration' ? 'selected' : '' }}>Administration</option>
                                    <option value="support" {{ old('department') == 'support' ? 'selected' : '' }}>Support</option>
                                    <option value="management" {{ old('department') == 'management' ? 'selected' : '' }}>Management</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Joining Date <span class="text-red-500">*</span></label>
                                <input type="date" name="joining_date" required value="{{ old('joining_date') }}" id="joining_date" onchange="updateStatusBadge()"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Employment Type <span class="text-red-500">*</span></label>
                                <select name="employment_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all bg-white">
                                    <option value="">Select</option>
                                    <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full-Time</option>
                                    <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part-Time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Current Status</label>
                                <div class="flex items-center gap-3">
                                    <span id="statusIndicator" class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-gray-100 text-gray-500 border border-gray-200">
                                        Awaiting Date
                                    </span>
                                    <input type="hidden" name="status" id="computedStatus" value="Active">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Financial & Bank -->
                <div class="xl:col-span-5 space-y-8">
                    <!-- Salary Details -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Salary Configuration</h2>
                                <p class="text-sm text-gray-500">Compensation structure and mode</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Basic Salary (₹) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₹</span>
                                    <input type="text" name="basic_salary" id="basic_salary" required value="{{ old('basic_salary') }}"
                                           oninput="restrictSalary(this)"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Salary Cycle <span class="text-red-500">*</span></label>
                                <select name="salary_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all bg-white">
                                    <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="hourly" {{ old('salary_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                    <option value="yearly" {{ old('salary_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Payment Mode <span class="text-red-500">*</span></label>
                                <select name="payment_method" id="payment_method" required onchange="toggleBankDetails()"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all bg-white">
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div id="bankDetailsCard" class="bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Bank Repository</h2>
                                <p class="text-sm text-gray-500">Direct deposit information</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 bank-detail-field">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all placeholder:text-gray-300"
                                       placeholder="e.g. HDFC Bank">
                            </div>
                            <div class="bank-detail-field">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Account Number</label>
                                <input type="text" name="account_number" id="account_number" maxlength="18" value="{{ old('account_number') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all placeholder:text-gray-300"
                                       placeholder="XXXX XXXX XXXX">
                            </div>
                            <div class="bank-detail-field">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">IFSC Code</label>
                                <input type="text" name="IFSC_code" id="IFSC_code" maxlength="11" value="{{ old('IFSC_code') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-4 focus:ring-amber-100 focus:border-amber-500 transition-all uppercase placeholder:text-gray-300"
                                       placeholder="HDFC0001234">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width Rows -->
            <div class="space-y-8 mt-8">
                <!-- Education Details -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Academic Background</h2>
                                <p class="text-sm text-gray-500">Educational qualifications and certifications</p>
                            </div>
                        </div>
                        <button type="button" onclick="addEducationEntry()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl font-bold text-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Append Qualification
                        </button>
                    </div>
                    
                    <div id="educationEntries" class="space-y-6">
                        <!-- Education Entry Template -->
                        <div class="education-entry bg-gray-50/50 p-6 rounded-2xl border border-gray-100 group">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Degree <span class="text-red-500">*</span></label>
                                    <input type="text" name="education_degree[0]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white" placeholder="B.Tech, MBA etc.">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Institution <span class="text-red-500">*</span></label>
                                    <input type="text" name="education_institution[0]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white" placeholder="University Name">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Passing Year <span class="text-red-500">*</span></label>
                                    <input type="number" name="education_year[0]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white" placeholder="2020" oninput="if(this.value > {{ date('Y') }}) this.value = {{ date('Y') }};">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Grade/Percentage</label>
                                    <input type="text" name="education_grade[0]" oninput="restrictGrade(this)"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white" placeholder="85.5%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Experience Details -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Career History</h2>
                                <p class="text-sm text-gray-500">Previous roles and professional expertise</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <select name="experience_type" id="experience_type" onchange="toggleExperienceFields()" 
                                    class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-orange-500 transition-all outline-none">
                                <option value="fresher" {{ old('experience_type') == 'fresher' ? 'selected' : '' }}>Fresher</option>
                                <option value="experienced" {{ old('experience_type') == 'experienced' ? 'selected' : '' }}>Experienced</option>
                            </select>
                            <button type="button" id="addExpBtn" onclick="addExperienceEntry()" 
                                    class="hidden inline-flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-700 hover:bg-orange-100 rounded-xl font-bold text-sm transition-all border border-orange-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Professional Record
                            </button>
                        </div>
                    </div>
                    
                    <div id="experienceContainer" class="hidden">
                        <div id="experienceEntries" class="space-y-6">
                            <!-- Experience Entry Template -->
                            <div class="experience-entry bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Organization <span class="text-red-500">*</span></label>
                                        <input type="text" name="experience_organization[0]" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white" placeholder="Company Name">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role/Position <span class="text-red-500">*</span></label>
                                        <input type="text" name="experience_role[0]" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white" placeholder="Software Engineer">
                                    </div>
                                    <div class="lg:col-span-1">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Start Date <span class="text-red-500">*</span></label>
                                        <input type="date" name="experience_start_date[0]" onchange="updateMinEndDate(this, 0)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white">
                                    </div>
                                    <div class="lg:col-span-1">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">End Date <span class="text-red-500">*</span></label>
                                        <input type="date" name="experience_end_date[0]" id="experience_end_date_0" onchange="calculateTotalExperience(0)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white">
                                    </div>
                                    <div class="lg:col-span-1">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tenure</label>
                                        <input type="text" name="experience_total[0]" readonly class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 font-medium" placeholder="Auto-calculated">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Assignment Section -->
                <div id="academicAssignmentSection" class="hidden bg-white rounded-3xl shadow-sm border border-gray-100/80 p-8 hover:shadow-md transition-all">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C12 2.253 4 2.253 6 4.253v13c0 5.747-2.253 8-8 8v13c0 5.747-2.253 8 8 8v13c0 5.747-2.253 8-8-8z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Academic Assignment</h2>
                            <p class="text-sm text-gray-500">Subject and class allocations for teachers</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Classes -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Assigned Classes</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @php
                                    $classes = ['5th','6th','7th','8th','9th','10th','11th','12th'];
                                    $selectedClasses = old('assigned_classes') ? (is_array(old('assigned_classes')) ? old('assigned_classes') : explode(', ', old('assigned_classes'))) : [];
                                @endphp
                                @foreach($classes as $class)
                                    <label class="group relative flex items-center justify-center p-3 rounded-xl border border-gray-100 hover:border-purple-200 hover:bg-purple-50 transition-all cursor-pointer has-[:checked]:bg-purple-600 has-[:checked]:border-purple-600 has-[:checked]:shadow-lg has-[:checked]:shadow-purple-200">
                                        <input type="checkbox" name="assigned_classes[]" value="{{$class}}" @if(in_array($class, $selectedClasses)) checked @endif class="peer hidden">
                                        <span class="text-sm font-bold text-gray-600 group-hover:text-purple-700 peer-checked:text-white">{{$class}}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Subjects -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">Assigned Subjects</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @php
                                    $subjects = ['Math', 'Science', 'English', 'Hindi', 'History', 'Physics', 'Chemistry', 'Biology', 'IT'];
                                    $selectedSubjects = old('assigned_subjects') ? (is_array(old('assigned_subjects')) ? old('assigned_subjects') : explode(', ', old('assigned_subjects'))) : [];
                                @endphp
                                @foreach($subjects as $sub)
                                    <label class="group relative flex items-center justify-center p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition-all cursor-pointer has-[:checked]:bg-blue-600 has-[:checked]:border-blue-600 has-[:checked]:shadow-lg has-[:checked]:shadow-blue-200">
                                        <input type="checkbox" name="assigned_subjects[]" value="{{$sub}}" @if(in_array($sub, $selectedSubjects)) checked @endif class="peer hidden">
                                        <span class="text-sm font-bold text-gray-600 group-hover:text-blue-700 peer-checked:text-white">{{$sub}}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Footer Actions -->
            <div class="flex items-center justify-end gap-6 pt-10 border-t border-gray-200 mt-12 pb-20">
                <a href="{{ route('employee.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-900 transition flex items-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Discard Changes
                </a>
                <button type="submit" class="px-10 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-200 transition-all active:scale-95 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Save & Initialize Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Validation Logic
    function restrictPhone(input) {
        let value = input.value.replace(/\D/g, ''); 
        if (value && !['7', '8', '9'].includes(value[0])) {
            value = '';
        }
        input.value = value.substring(0, 10);
        // While typing, hide error if they are making progress
        input.classList.remove('border-red-500', 'ring-red-50');
        document.getElementById('phoneError').classList.add('hidden');
    }

    function checkPhone(input) {
        const error = document.getElementById('phoneError');
        if (input.value && input.value.length < 10) {
            input.classList.add('border-red-500', 'ring-red-50');
            error.classList.remove('hidden');
        } else {
            input.classList.remove('border-red-500', 'ring-red-50');
            error.classList.add('hidden');
        }
    }

    function restrictSalary(input) {
        let value = input.value.replace(/[^0-9.]/g, '');
        const parts = value.split('.');
        if (parts.length > 2) value = parts[0] + '.' + parts.slice(1).join('');
        input.value = value;
    }

    function restrictGrade(input) {
        // Allow numbers, one decimal point and %
        let value = input.value.replace(/[^0-9.%]/g, '');
        const parts = value.split('.');
        if (parts.length > 2) value = parts[0] + '.' + parts.slice(1).join('');
        
        // Ensure percent sign only at the end
        if (value.includes('%')) {
            value = value.replace(/%/g, '') + '%';
        }

        // Check range (if numeric)
        const numericPart = parseFloat(value);
        if (!isNaN(numericPart) && numericPart > 100) {
            value = '100' + (value.includes('%') ? '%' : '');
        }

        input.value = value;
    }

    function setupDOB() {
        const dob = document.getElementById('date_of_birth');
        const today = new Date();
        const max = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate()).toISOString().split('T')[0];
        dob.max = max;
    }

    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        const phone = document.getElementById('phone');
        const dob = document.getElementById('date_of_birth');
        let valid = true;

        if (phone.value.length !== 10) {
            phone.classList.add('border-red-500', 'ring-red-50');
            document.getElementById('phoneError').classList.remove('hidden');
            valid = false;
        }

        if (dob.value) {
            const birth = new Date(dob.value);
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            if (today.getMonth() < birth.getMonth() || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--;
            if (age < 18) {
                dob.classList.add('border-red-500', 'ring-red-50');
                document.getElementById('dobError').classList.remove('hidden');
                valid = false;
            }
        }

        if (!valid) {
            e.preventDefault();
            document.querySelector('.border-red-500')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

let educationCount = 1;
let experienceCount = 1;

function calculateTotalExperience(index) {
    const startDate = document.querySelector(`input[name="experience_start_date[${index}]"]`);
    const endDate = document.querySelector(`input[name="experience_end_date[${index}]"]`);
    const totalField = document.querySelector(`input[name="experience_total[${index}]"]`);
    
    if (startDate && endDate && totalField) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        
        if (start && end && !isNaN(start) && !isNaN(end) && end > start) {
            let years = end.getFullYear() - start.getFullYear();
            let months = end.getMonth() - start.getMonth();
            if (months < 0) { years--; months += 12; }
            let text = '';
            if (years > 0) text += years + ' year' + (years > 1 ? 's' : '');
            if (months > 0) text += (text ? ' ' : '') + months + ' month' + (months > 1 ? 's' : '');
            totalField.value = text || '0 months';
        }
    }
}

function updateMinEndDate(input, index) {
    if (input.value) {
        const endDateInput = document.getElementById(`experience_end_date_${index}`);
        if (endDateInput) {
            endDateInput.min = input.value;
            calculateTotalExperience(index);
        }
    }
}

function toggleAcademicAssignment() {
    const desigSelect = document.getElementById('designation');
    if (!desigSelect) return;
    const desig = desigSelect.value.toLowerCase();
    const section = document.getElementById('academicAssignmentSection');
    if (desig === 'teacher') {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
        section.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
}

function toggleExperienceFields() {
    const type = document.getElementById('experience_type').value;
    const container = document.getElementById('experienceContainer');
    const addBtn = document.getElementById('addExpBtn');
    const inputs = container.querySelectorAll('input');
    
    if (type === 'experienced') {
        container.classList.remove('hidden');
        addBtn.classList.remove('hidden');
        inputs.forEach(input => {
            if (!input.name.includes('total')) input.setAttribute('required', 'required');
        });
    } else {
        container.classList.add('hidden');
        addBtn.classList.add('hidden');
        inputs.forEach(input => {
            input.removeAttribute('required');
            input.value = '';
        });
    }
}

function toggleBankDetails() {
    const method = document.getElementById('payment_method').value;
    const fields = document.querySelectorAll('.bank-detail-field');
    const inputs = document.querySelectorAll('#bank_name, #account_number, #IFSC_code');
    
    if (method === 'bank_transfer') {
        fields.forEach(f => f.style.display = 'block');
        inputs.forEach(i => i.setAttribute('required', 'required'));
    } else {
        fields.forEach(f => f.style.display = 'none');
        inputs.forEach(i => { i.removeAttribute('required'); i.value = ''; });
    }
}

function updateStatusBadge() {
    const dateInput = document.getElementById('joining_date').value;
    const indicator = document.getElementById('statusIndicator');
    if (!dateInput) return;
    const joiningDate = new Date(dateInput);
    const today = new Date();
    today.setHours(0,0,0,0);
    joiningDate.setHours(0,0,0,0);
    
    if (joiningDate > today) {
        indicator.textContent = 'Inactive (Future)';
        indicator.className = 'px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-100';
    } else {
        indicator.textContent = 'Active';
        indicator.className = 'px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-100';
    }
}

function addEducationEntry() {
    const grid = document.getElementById('educationEntries');
    const index = grid.children.length;
    const entry = document.createElement('div');
    entry.className = 'education-entry bg-gray-50/50 p-6 rounded-2xl border border-gray-100 relative group animate-fade-in';
    entry.innerHTML = `
        <button type="button" onclick="this.closest('.education-entry').remove()" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Degree <span class="text-red-500">*</span></label>
                <input type="text" name="education_degree[${index}]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white">
            </div>
            <div class="md:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Institution <span class="text-red-500">*</span></label>
                <input type="text" name="education_institution[${index}]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Passing Year <span class="text-red-500">*</span></label>
                <input type="number" name="education_year[${index}]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white" oninput="if(this.value > {{ date('Y') }}) this.value = {{ date('Y') }};">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Grade/Percentage</label>
                <input type="text" name="education_grade[${index}]" oninput="restrictGrade(this)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-white">
            </div>
        </div>
    `;
    grid.appendChild(entry);
}

function addExperienceEntry() {
    const grid = document.getElementById('experienceEntries');
    const index = grid.children.length;
    const entry = document.createElement('div');
    entry.className = 'experience-entry bg-gray-50/50 p-6 rounded-2xl border border-gray-100 relative group animate-fade-in';
    entry.innerHTML = `
        <button type="button" onclick="this.closest('.experience-entry').remove()" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Organization <span class="text-red-500">*</span></label>
                <input type="text" name="experience_organization[${index}]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Role <span class="text-red-500">*</span></label>
                <input type="text" name="experience_role[${index}]" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="experience_start_date[${index}]" required onchange="updateMinEndDate(this, ${index})" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="experience_end_date[${index}]" id="experience_end_date_${index}" required onchange="calculateTotalExperience(${index})" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tenure</label>
                <input type="text" name="experience_total[${index}]" readonly class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 font-medium">
            </div>
        </div>
    `;
    grid.appendChild(entry);
}

document.addEventListener('DOMContentLoaded', () => {
    toggleAcademicAssignment();
    toggleExperienceFields();
    toggleBankDetails();
    updateStatusBadge();
    
    @if(old('education_degree'))
        @foreach(old('education_degree') as $idx => $val)
            @if($idx > 0) addEducationEntry(); @endif
            const deg = document.querySelectorAll(`input[name^="education_degree"]`)[{{$idx}}];
            if(deg) deg.value = @json(old("education_degree.$idx"));
            const ins = document.querySelectorAll(`input[name^="education_institution"]`)[{{$idx}}];
            if(ins) ins.value = @json(old("education_institution.$idx"));
            const yr = document.querySelectorAll(`input[name^="education_year"]`)[{{$idx}}];
            if(yr) yr.value = @json(old("education_year.$idx"));
            const gr = document.querySelectorAll(`input[name^="education_grade"]`)[{{$idx}}];
            if(gr) gr.value = @json(old("education_grade.$idx"));
        @endforeach
    @endif

    @if(old('experience_organization'))
        @foreach(old('experience_organization') as $idx => $val)
            @if($idx > 0) addExperienceEntry(); @endif
            const org = document.querySelectorAll(`input[name^="experience_organization"]`)[{{$idx}}];
            if(org) org.value = @json(old("experience_organization.$idx"));
            const rol = document.querySelectorAll(`input[name^="experience_role"]`)[{{$idx}}];
            if(rol) rol.value = @json(old("experience_role.$idx"));
            const sd = document.querySelectorAll(`input[name^="experience_start_date"]`)[{{$idx}}];
            if(sd) sd.value = @json(old("experience_start_date.$idx"));
            const ed = document.querySelectorAll(`input[name^="experience_end_date"]`)[{{$idx}}];
            if(ed) ed.value = @json(old("experience_end_date.$idx"));
            const tot = document.querySelectorAll(`input[name^="experience_total"]`)[{{$idx}}];
            if(tot) tot.value = @json(old("experience_total.$idx"));
        @endforeach
    @endif

    setupDOB();
});
</script>

<style>
@keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
@keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
.animate-shake { animation: shake 0.4s ease-in-out; }
</style>
@endsection