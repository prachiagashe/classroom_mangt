@extends('layouts.app')

@section('title', 'Salary History')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Salary History</h1>
        <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Summary Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Paid</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salaryRecords->where('payment_status', 'paid')->sum('paid_amount'), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pending</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salaryRecords->where('payment_status', 'pending')->sum('net_salary'), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Deductions</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salaryRecords->sum('deduction_amount'), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bonus</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salaryRecords->sum('bonus_amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary History Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200 sticky top-0 z-10">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">Month</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Year</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Basic Salary</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Deduction Amount</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Bonus Amount</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Net Salary</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Paid Amount</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Status</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Date</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Method</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($salaryRecords as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-900">{{ date('F', mktime(0, 0, 0, $record->month, 1)) }}</td>
                            <td class="p-4 text-gray-900">{{ $record->year }}</td>
                            <td class="p-4 text-gray-900">₹{{ number_format($record->basic_salary, 2) }}</td>
                            <td class="p-4 text-gray-900">₹{{ number_format($record->deduction_amount, 2) }}</td>
                            <td class="p-4 text-gray-900">₹{{ number_format($record->bonus_amount, 2) }}</td>
                            <td class="p-4 text-gray-900">₹{{ number_format($record->net_salary, 2) }}</td>
                            <td class="p-4 text-gray-900">₹{{ number_format($record->paid_amount, 2) }}</td>
                            <td class="p-4">
                                @switch($record->payment_status)
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                        @break
                                    @case('partial')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Partial
                                        </span>
                                        @break
                                    @case('paid')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="p-4 text-gray-900">
                                {{ $record->payment_date ? date('d M Y', strtotime($record->payment_date)) : '-' }}
                            </td>
                            <td class="p-4 text-gray-900">
                                {{ $record->payment_method ?? '-' }}
                            </td>
                            <td class="p-4 text-gray-900">
                                <span class="text-sm" title="{{ $record->remarks }}">
                                    {{ $record->remarks ? Str::limit($record->remarks, 30) : '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No salary records found</p>
                                    <p class="text-sm mt-2">You don't have any salary records yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
