@extends('layouts.app')

@section('title', 'Salary Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Salary Details</h1>
                    <p class="text-gray-600 mt-3 text-lg">View detailed salary information for {{ $employee->full_name }}</p>
                </div>
                <a href="{{ route('salary.index') }}" 
                   class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7h18"/>
                    </svg>
                    Back to Salary Management
                </a>
            </div>
        </div>

        <!-- Employee Information Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $employee->full_name }}</h2>
                    <p class="text-gray-600">{{ $employee->designation }} • {{ ucfirst($employee->department) }}</p>
                    <p class="text-sm text-gray-500">Employee Code: {{ $employee->employee_code }}</p>
                </div>
            </div>
        </div>

        <!-- Salary Information Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Salary Information</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900">
                        <span class="text-2xl font-bold text-green-600">₹{{ number_format($employee->basic_salary, 0, '.', ',') }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Salary Type</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900 capitalize">
                        {{ $employee->salary_type }}
                    </div>
                </div>
            </div>

            <!-- Bank Details Card (if bank transfer) -->
        @if($employee->payment_method == 'bank_transfer')
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7a2 2 0 00-2 2v11a2 2 0 002-2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Bank Details</h2>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900">
                        {{ $employee->bank_name ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900">
                        {{ $employee->account_number ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900">
                        {{ $employee->IFSC_code ?? 'N/A' }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Payment Status Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Payment Status</h2>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900">
                        <span class="px-2 py-1 text-sm font-semibold rounded-full 
                            @if($employee->basic_salary && $employee->basic_salary > 0) bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif">
                            @if($employee->basic_salary && $employee->basic_salary > 0)
                                Paid
                            @else
                                Pending
                            @endif
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Payment Date</label>
                    <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-900">
                        {{ $employee->updated_at ? $employee->updated_at->format('F j, Y') : 'Not available' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('salary.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7h18"/>
                </svg>
                Back to Salary Management
            </a>
            <button class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all font-medium shadow-sm">
                Mark as Paid
            </button>
        </div>
    </div>
</div>
@endsection
