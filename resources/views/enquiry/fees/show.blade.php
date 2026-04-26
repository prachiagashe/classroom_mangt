@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <!-- Page Heading -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('enquiry.fees') }}" 
           class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Fee Details</h1>
            <p class="text-gray-500 mt-1">{{ $enquiry->first_name }} {{ $enquiry->surname }} - Complete Fee Information</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div id="success-alert" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center shadow-sm transition-opacity duration-500">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('success-alert');
                if(alert) {
                    alert.style.opacity = '0';
                    setTimeout(function() { alert.style.display = 'none'; }, 500);
                }
            }, 10000);
        </script>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Student Information Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-lg font-bold">
                    {{ substr($enquiry->first_name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $enquiry->first_name }} {{ $enquiry->surname }}</h2>
                    <p class="text-gray-500">Class: {{ $enquiry->class }} | Contact: {{ $enquiry->parent_mobile }}</p>
                </div>
            </div>
            <div class="text-right flex flex-col items-end gap-2">
                <div>
                    <p class="text-sm text-gray-500">Admission Date</p>
                    <p class="font-medium">{{ $admission->created_at->format('d M Y') }}</p>
                </div>
                @if($pendingAmount > 0)
                <form action="{{ route('enquiry.fees.reminder.whatsapp', $admission->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition text-xs font-medium flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.067 2.877 1.215 3.076.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Send Fee Reminder
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Fee Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <!-- Original Fee -->
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200">
            <p class="text-xs text-gray-600 mb-1">Original Fee</p>
            <p class="text-xl font-bold text-gray-900">₹{{ number_format($totalFees, 2) }}</p>
        </div>

        <!-- Discount -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl border border-orange-200">
            <p class="text-xs text-orange-600 mb-1">Discount</p>
            <p class="text-xl font-bold text-orange-700">₹{{ number_format($enquiry->discount_fees ?? 0, 2) }}</p>
        </div>

        <!-- Final Fee -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
            <p class="text-xs text-blue-600 mb-1">Final Fee</p>
            <p class="text-xl font-bold text-blue-700">₹{{ number_format($finalFees, 2) }}</p>
        </div>

        <!-- Paid Amount -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
            <p class="text-xs text-green-600 mb-1">Paid Amount</p>
            <p class="text-xl font-bold text-green-700">₹{{ number_format($totalPaid, 2) }}</p>
        </div>

        <!-- Pending Amount -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl border border-red-200">
            <p class="text-xs text-red-600 mb-1">Pending Amount</p>
            <p class="text-xl font-bold text-red-700">₹{{ number_format($pendingAmount, 2) }}</p>
        </div>

        <!-- Payment Mode -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
            <p class="text-xs text-purple-600 mb-1">Payment Mode</p>
            @php
                $paymentMode = $admission->payment_mode ?? ($installments->first()->payment_mode ?? 'cash');
                $paymentMode = strtolower(trim($paymentMode));
                $paymentModeDisplay = match($paymentMode) {
                    'cash' => 'Cash',
                    'online' => 'Online',
                    'installment' => 'Installment',
                    '' => 'Not Set',
                    default => ucfirst($paymentMode)
                };
            @endphp
            <p class="text-xl font-bold text-purple-700">{{ $paymentModeDisplay }}</p>
        </div>
    </div>

    <!-- Installment Schedule -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Installment Schedule</h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ ucfirst($admission->installment_type ?? 'One-time') }} • {{ $admission->installment_count ?? 1 }} Installment(s)
                </p>
            </div>
            @if($pendingAmount > 0)
            <button onclick="openEditScheduleModal()" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 border border-blue-200 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Schedule
            </button>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">No.</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Due Date</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Date</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Amount Due</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Paid Amount</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Pending</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($expectedInstallments as $inst)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium">{{ $inst['installment_number'] }}</td>
                            <td class="p-4 {{ $inst['due_date']->isPast() && $inst['status'] !== 'Paid' ? 'text-red-600 font-semibold' : '' }}">
                                {{ $inst['due_date']->format('d M Y') }}
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ $inst['payment_date'] ? \Carbon\Carbon::parse($inst['payment_date'])->format('d M Y') : '-' }}
                            </td>
                            <td class="p-4 font-medium text-gray-900">₹{{ number_format($inst['amount'], 2) }}</td>
                            <td class="p-4 text-green-600">₹{{ number_format($inst['paid_amount'], 2) }}</td>
                            <td class="p-4 {{ $inst['pending_amount'] > 0 ? 'text-red-600' : 'text-gray-500' }}">₹{{ number_format($inst['pending_amount'], 2) }}</td>
                            <td class="p-4">
                                @if($inst['status'] === 'Paid Installment')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Paid Installment</span>
                                @elseif($inst['status'] === 'Partial')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Partial</span>
                                @elseif($inst['status'] === 'Overdue')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Overdue</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment History Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                <button onclick="openPaymentModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Record New Payment
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">Installment No.</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Amount</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Mode</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Date</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Transaction ID</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Remarks</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($installments as $installment)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium">{{ $loop->count - $loop->index }}</td>
                            <td class="p-4 font-semibold text-green-600">₹{{ number_format($installment['amount'], 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                    {{ $installment['payment_mode'] }}
                                </span>
                            </td>
                            <td class="p-4">{{ $installment['payment_date']->format('d M Y') }}</td>
                            <td class="p-4 font-mono text-sm">{{ $installment['transaction_id'] ?? 'N/A' }}</td>
                            <td class="p-4 text-gray-600">{{ $installment['remarks'] ?? '-' }}</td>
                            <td class="p-4 flex items-center gap-2">
                                <form action="{{ route('enquiry.fees.payment.receipt', $installment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-2.5 py-1 rounded border border-blue-200 text-xs font-medium inline-flex items-center gap-1 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Send Receipt
                                    </button>
                                </form>
                                <a href="{{ route('enquiry.fees.payment.receipt.download', $installment->id) }}" class="text-gray-700 hover:text-gray-900 bg-gray-100 px-2.5 py-1 rounded border border-gray-300 text-xs font-medium inline-flex items-center gap-1 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Download PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-600">No payment history found</h3>
                                    <p class="text-gray-400 text-sm">This student hasn't made any payments yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr class="border-t border-gray-200">
                        <td class="p-4 text-gray-500 font-medium text-sm">Total Payments: {{ $installments->count() }}</td>
                        <td class="p-4 font-bold text-green-600 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-700 mr-1 block md:inline">Total Paid:</span>
                            ₹{{ number_format($totalPaid, 2) }}
                        </td>
                        <td colspan="3"></td>
                        <td class="p-4 font-bold {{ $pendingAmount > 0 ? 'text-red-600' : 'text-green-600' }} whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-700 mr-1 block md:inline">Remaining:</span>
                            ₹{{ number_format($pendingAmount, 2) }}
                        </td>
                        <td class="p-4 text-right">
                            @if($pendingAmount <= 0)
                                <span class="px-4 py-1.5 text-sm font-bold rounded-full bg-green-100 text-green-700 inline-block shadow-sm">Complete</span>
                            @else
                                <span class="px-4 py-1.5 text-sm font-bold rounded-full bg-yellow-100 text-yellow-700 inline-block shadow-sm">Pending</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- Edit Schedule Modal -->
<div id="editScheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeEditScheduleModal()"></div>

        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:w-full sm:max-w-md sm:align-middle">
            <form action="{{ route('enquiry.fees.schedule.update', $admission->id) }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Edit Installment Schedule</h3>
                        <button type="button" onclick="closeEditScheduleModal()" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <p class="text-sm text-gray-600 mb-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        Total Final Fee: <strong>₹{{ number_format($finalFees, 2) }}</strong><br>
                        This will automatically recalculate and distribute the pending amount across the newly selected installment count.
                    </p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Number of Installments</label>
                        <input type="number" name="installment_count" required min="1" max="24"
                               value="{{ $admission->installment_count ?? 1 }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Installment Frequency</label>
                        <select name="installment_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="monthly" {{ strtolower($admission->installment_type) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="weekly" {{ strtolower($admission->installment_type) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="quarterly" {{ strtolower($admission->installment_type) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ strtolower($admission->installment_type) === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date for First Installment</label>
                        <input type="date" name="installment_start_date" required 
                               value="{{ $admission->installment_start_date ? \Carbon\Carbon::parse($admission->installment_start_date)->format('Y-m-d') : $admission->created_at->format('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl border-t border-gray-200">
                    <button type="button" onclick="closeEditScheduleModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closePaymentModal()"></div>

        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:w-full sm:max-w-md sm:align-middle">
            <form action="{{ route('enquiry.fees.payment.store', $admission->id) }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Record New Payment</h3>
                        <button type="button" onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-4">
                    @if($pendingAmount <= 0)
                        <div class="bg-green-50 text-green-700 p-4 rounded-lg font-medium text-center border border-green-200 shadow-sm mb-4">
                            <svg class="w-5 h-5 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Fees already completed
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₹)</label>
                        <input type="number" name="amount" required step="0.01" min="1" max="{{ $pendingAmount > 0 ? $pendingAmount : 0 }}" 
                               value="{{ $pendingAmount > 0 ? $pendingAmount : 0 }}"
                               {{ $pendingAmount <= 0 ? 'disabled' : '' }}
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 cursor-not-allowed">
                        <p class="text-xs {{ $pendingAmount <= 0 ? 'text-green-600 font-semibold' : 'text-gray-500' }} mt-1">Pending Amount: ₹{{ number_format($pendingAmount, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Mode</label>
                        <select name="payment_mode" required {{ $pendingAmount <= 0 ? 'disabled' : '' }} class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 cursor-not-allowed">
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                            <option value="cheque">Cheque</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" {{ $pendingAmount <= 0 ? 'disabled' : '' }}
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks (Optional)</label>
                        <textarea name="remarks" rows="2" {{ $pendingAmount <= 0 ? 'disabled' : '' }}
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 cursor-not-allowed"
                                  placeholder="Transaction ID, Cheque number, etc."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl border-t border-gray-200">
                    <button type="button" onclick="closePaymentModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" {{ $pendingAmount <= 0 ? 'disabled' : '' }}
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        @if($pendingAmount <= 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        @endif
                        Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function openEditScheduleModal() {
        document.getElementById('editScheduleModal').classList.remove('hidden');
    }

    function closeEditScheduleModal() {
        document.getElementById('editScheduleModal').classList.add('hidden');
    }
</script>

@endsection
