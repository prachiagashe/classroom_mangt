@extends('layouts.app')

@section('title', 'My Fees')

@section('page-title', 'Fee Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Fee Details</h1>
        <p class="text-gray-600">View your fee structure and payment status.</p>
    </div>

    <!-- Alert Messages -->
    @if($isOverdue)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Payment Overdue!</h3>
                    <p class="text-sm text-red-700">
                        Your payment is {{ $overdueDays }} days overdue. Please make the payment as soon as possible to avoid late fees.
                    </p>
                </div>
            </div>
        </div>
    @elseif($isDueSoon)
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Payment Due Soon</h3>
                    <p class="text-sm text-yellow-700">
                        Your next payment is due in {{ (int)$nextDueDate->diffInDays(\Carbon\Carbon::now()) }} days on {{ $nextDueDate->format('M d, Y') }}. Please ensure timely payment.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Fee Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Fee -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.165 0-2.25-.638-.563-1.5-1.5H4.5c-.832 0-1.5.563-1.5-1.5v15c0 .847.544 1.5 1.5h15c.847 0 1.544 1.5 1.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalFee, 2) }}</p>
                    <p class="text-sm text-gray-600">Total Fee</p>
                </div>
            </div>
        </div>

        <!-- Paid Amount -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 7 0 017 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($paidAmount, 2) }}</p>
                    <p class="text-sm text-gray-600">Paid Amount</p>
                </div>
            </div>
        </div>

        <!-- Pending Amount -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($pendingAmount, 2) }}</p>
                    <p class="text-sm text-gray-600">Pending Amount</p>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                @php
                $statusBgClass = $paymentStatus == 'paid' ? 'bg-green-100' : ($paymentStatus == 'overdue' ? 'bg-red-100' : 'bg-yellow-100');
                $statusIconClass = $paymentStatus == 'paid' ? 'text-green-600' : ($paymentStatus == 'overdue' ? 'text-red-600' : 'text-yellow-600');
                @endphp
                <div class="w-12 h-12 {{ $statusBgClass }} rounded-lg flex items-center justify-center mr-4">
                    @if($paymentStatus == 'paid')
                        <svg class="w-6 h-6 {{ $statusIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 7 0 017 0z"/>
                        </svg>
                    @elseif($paymentStatus == 'overdue')
                        <svg class="w-6 h-6 {{ $statusIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 {{ $statusIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-900">
                        @if($paymentStatus == 'paid')
                            Paid
                        @elseif($paymentStatus == 'overdue')
                            Overdue
                        @else
                            Pending
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Payment Status</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Installment Details -->
    @if($installmentType && $installmentType != 'full')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9v-2a3 3 0 00-6 0H5a3 3 0 00-3 3v12a3 3 0 006 6h-2a3 3 0 00-3 3z"/>
                </svg>
                <h2 class="text-xl font-semibold text-gray-900">Installment Details</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Installment Type -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Installment Type</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($installmentType) }}</p>
                </div>

                <!-- Total Installments -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total Installments</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $installmentCount }}</p>
                </div>

                <!-- Installment Amount -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Installment Amount</p>
                    <p class="text-lg font-semibold text-gray-900">₹{{ number_format($installmentAmount, 2) }}</p>
                </div>

                <!-- Paid Installments -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Paid Installments</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $paidInstallments }} / {{ $installmentCount }}</p>
                </div>
            </div>

            <!-- Installment History -->
            @if(count($installments) > 0)
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Installment Schedule & Payment History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installment #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($installments as $installment)
                                    @php
                                    if ($installment['is_fully_paid']) {
                                        $statusBgClass = 'bg-green-100 text-green-800';
                                        $statusIcon = '✓';
                                    } elseif ($installment['is_overdue']) {
                                        $statusBgClass = 'bg-red-100 text-red-800';
                                        $statusIcon = '!';
                                    } elseif ($installment['is_partially_paid']) {
                                        $statusBgClass = 'bg-blue-100 text-blue-800';
                                        $statusIcon = '◐';
                                    } else {
                                        $statusBgClass = 'bg-yellow-100 text-yellow-800';
                                        $statusIcon = '○';
                                    }
                                    
                                    $progressColor = $installment['payment_progress'] == 100 ? 'bg-green-500' : ($installment['payment_progress'] > 0 ? 'bg-blue-500' : 'bg-gray-300');
                                    @endphp
                                    <tr class="{{ $installment['is_overdue'] ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $installment['number'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $installment['due_date'] }}
                                            @if($installment['is_overdue'])
                                                <span class="ml-2 text-xs text-red-600 font-medium">({{ $installment['due_date_raw']->diffInDays(\Carbon\Carbon::now()) }} days overdue)</span>
                                            @elseif($installment['is_past'] && !$installment['is_fully_paid'])
                                                <span class="ml-2 text-xs text-orange-600 font-medium">(Past due)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($installment['paid_amount'] > 0)
                                                <span class="font-medium text-green-600">₹{{ number_format($installment['paid_amount'], 2) }}</span>
                                            @else
                                                <span class="text-gray-400">₹0.00</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($installment['remaining_amount'] > 0)
                                                <span class="font-medium {{ $installment['is_overdue'] ? 'text-red-600' : 'text-orange-600' }}">₹{{ number_format($installment['remaining_amount'], 2) }}</span>
                                            @else
                                                <span class="text-green-600 font-medium">₹0.00</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBgClass }}">
                                                <span class="mr-1">{{ $statusIcon }}</span>
                                                @if($installment['is_fully_paid'])
                                                    Paid
                                                @elseif($installment['is_partially_paid'])
                                                    Partially Paid
                                                @elseif($installment['is_overdue'])
                                                    Overdue
                                                @else
                                                    Pending
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Enhanced Installment Summary -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 7 0 017 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-green-600">Fully Paid</p>
                                    <p class="text-lg font-semibold text-green-900">{{ $paidInstallments }} of {{ $installmentCount }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-600">Partially Paid</p>
                                    <p class="text-lg font-semibold text-blue-900">{{ $partiallyPaidInstallments }} of {{ $installmentCount }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-600">Pending</p>
                                    <p class="text-lg font-semibold text-yellow-900">{{ $installmentCount - $paidInstallments - $partiallyPaidInstallments }} of {{ $installmentCount }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-purple-600">Next Due</p>
                                    <p class="text-lg font-semibold text-purple-900">
                                        @if($nextDueDate)
                                            {{ $nextDueDate->format('M d, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Flexibility Notice -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800">Flexible Payment System</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    You can pay any amount you want towards your installments. Payments are automatically applied to the earliest due installments first. Partial payments are tracked and shown in the progress bars above.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Student Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"/>
            </svg>
            <h2 class="text-xl font-semibold text-gray-900">Student Information</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Student Name</p>
                <p class="text-lg font-medium text-gray-900">{{ $admission->student_name }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600 mb-1">Class</p>
                <p class="text-lg font-medium text-gray-900">{{ $admission->class }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600 mb-1">Roll Number</p>
                <p class="text-lg font-medium text-gray-900">{{ $admission->roll_number }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600 mb-1">Admission Date</p>
                <p class="text-lg font-medium text-gray-900">{{ $admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        @if($admission->remarks)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Remarks</p>
                <p class="text-gray-900">{{ $admission->remarks }}</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 flex justify-center">
        <a href="{{ route('student.dashboard') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7 0 017 0z"/>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>

<script>
// Auto-refresh fee stats every 30 seconds
setInterval(function() {
    fetch('{{ route("student.fees.stats") }}')
        .then(response => response.json())
        .then(data => {
            // Update sidebar notification badge if needed
            const badge = document.querySelector('.fee-notification-badge');
            if (badge && data.alertCount > 0) {
                badge.textContent = data.alertCount;
                badge.style.display = 'inline-flex';
            }
        })
        .catch(error => console.error('Error fetching fee stats:', error));
}, 30000);
</script>
@endsection
