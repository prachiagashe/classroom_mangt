@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">

    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Fees Management</h1>
            <p class="text-gray-500">Manage student fees and payment details</p>
        </div>
        <div class="bg-blue-600 p-4 rounded-xl shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>

    <!-- Fee Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Collection -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Collection</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalCollection, 2) }}</p>
                </div>
                <svg class="w-8 h-8 text-green-600 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Total Pending -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pending</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalPending, 2) }}</p>
                </div>
                <svg class="w-8 h-8 text-red-600 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Paid Students -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 rounded-xl border border-emerald-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Paid Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $paidCount }}</p>
                </div>
                <svg class="w-8 h-8 text-emerald-600 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Overdue Students -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Overdue Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overdueCount }}</p>
                </div>
                <svg class="w-8 h-8 text-orange-600 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Students Fee Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Confirmed Students Fee Details</h2>
                <div class="flex items-center gap-4">
                    <!-- Fee Status Filter -->
                    <form method="GET" action="{{ route('enquiry.fees') }}" id="feeStatusForm">
                        <select name="fee_status" onchange="document.getElementById('feeStatusForm').submit()" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white text-gray-700">
                            <option value="">All Fee Status</option>
                            <option value="paid" {{ request('fee_status') == 'paid' ? 'selected' : '' }}>Complete</option>
                            <option value="installment" {{ request('fee_status') == 'installment' ? 'selected' : '' }}>Partial</option>
                            <option value="pending" {{ request('fee_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="overdue" {{ request('fee_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                    </form>

                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search students..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="crm-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">Student Name</th>
                        <th class="whitespace-nowrap">Mode</th>
                        <th class="whitespace-nowrap">Contact</th>
                        <th class="whitespace-nowrap">Class</th>
                        <th class="whitespace-nowrap">Total</th>
                        <th class="whitespace-nowrap">Discount</th>
                        <th class="whitespace-nowrap">Paid</th>
                        <th class="whitespace-nowrap">Pending</th>
                        <th class="text-center whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody id="feesTableBody">
                    @forelse($enquiries as $enquiry)
                        <tr>
                            <!-- Student Name -->
                            <td class="py-3 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-full text-sm font-semibold text-white bg-gradient-to-br from-blue-500 to-indigo-600">
                                        {{ $enquiry ? substr($enquiry['student_name'] ?? 'N/A', 0, 1) : 'N' }}
                                    </div>
                                    <span class="text-sm font-semibold leading-5 text-gray-900 truncate max-w-[130px] block" title="{{ $enquiry ? ($enquiry['student_name'] ?? 'N/A') : 'N/A' }}">{{ $enquiry ? ($enquiry['student_name'] ?? 'N/A') : 'N/A' }}</span>
                                </div>
                            </td>

                            <!-- Payment Mode -->
                            <td>
                                <span class="crm-badge 
                                    @if($enquiry['payment_mode'] == 'Cash') crm-badge-info
                                    @elseif($enquiry['payment_mode'] == 'Online') crm-badge-success
                                    @elseif($enquiry['payment_mode'] == 'Installment') crm-badge-warning
                                    @else crm-badge-info @endif">
                                    {{ $enquiry['payment_mode'] }}
                                </span>
                            </td>

                            <!-- Contact -->
                            <td><span class="secondary-text">{{ $enquiry ? ($enquiry['contact'] ?? 'N/A') : 'N/A' }}</span></td>

                            <!-- Class -->
                            <td>
                                <span class="crm-badge crm-badge-info">
                                    {{ $enquiry ? ($enquiry['class'] ?? 'N/A') : 'N/A' }}
                                </span>
                            </td>

                            <!-- Total Fee -->
                            <td>
                                <span class="font-bold text-gray-900">₹{{ $enquiry ? number_format($enquiry['total_fee'] ?? 0, 2) : '0.00' }}</span>
                            </td>

                            <!-- Discount -->
                            <td>
                                <span class="secondary-text">₹{{ $enquiry ? number_format($enquiry['discount'] ?? 0, 2) : '0.00' }}</span>
                            </td>

                            <!-- Paid Amount -->
                            <td>
                                <span class="font-bold text-green-600">₹{{ $enquiry ? number_format($enquiry['paid_amount'] ?? 0, 2) : '0.00' }}</span>
                            </td>

                            <!-- Pending Amount -->
                            <td>
                                <span class="font-bold {{ $enquiry && $enquiry['pending_amount'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    ₹{{ $enquiry ? number_format($enquiry['pending_amount'] ?? 0, 2) : '0.00' }}
                                </span>
                            </td>

                            <!-- Fee Status -->
                            <td class="text-center">
                                <span class="crm-badge
                                    @if(str_contains(strtolower($enquiry['fee_status']), 'paid') || str_contains(strtolower($enquiry['fee_status']), 'complete')) crm-badge-success
                                    @elseif(str_contains(strtolower($enquiry['fee_status']), 'pending') || str_contains(strtolower($enquiry['fee_status']), 'partial')) crm-badge-warning
                                    @elseif(str_contains(strtolower($enquiry['fee_status']), 'overdue')) crm-badge-danger
                                    @else crm-badge-info @endif">
                                    {{ $enquiry ? ($enquiry['fee_status'] ?? 'Unknown') : 'Unknown' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                @if($enquiry)
                                    <a href="{{ route('enquiry.fees.show', $enquiry['admission_id']) }}" 
                                       class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all inline-flex items-center" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-12 text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-600">No students found</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-medium">{{ $enquiries->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $enquiries->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $enquiries->total() }}</span> students
                </p>
                
                <!-- Pagination Links -->
                <div class="fees-pagination">
                    {{ $enquiries->links() }}
                </div>

                <div class="hidden md:flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-100 rounded-full"></div>
                        <span class="text-gray-600">Paid: {{ $paidCount }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-100 rounded-full"></div>
                        <span class="text-gray-600">Overdue: {{ $overdueCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Real-time search functionality
let searchTimeout;

document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value.trim().toLowerCase();
    const tableBody = document.getElementById('feesTableBody');
    const rows = tableBody.getElementsByTagName('tr');
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Debounce search (300ms delay)
    searchTimeout = setTimeout(() => {
        Array.from(rows).forEach(row => {
            const studentName = row.cells[0]?.textContent.toLowerCase() || '';
            const parentName = row.cells[1]?.textContent.toLowerCase() || '';
            const contact = row.cells[2]?.textContent.toLowerCase() || '';
            const className = row.cells[3]?.textContent.toLowerCase() || '';
            
            const matches = studentName.includes(query) || 
                          parentName.includes(query) || 
                          contact.includes(query) || 
                          className.includes(query);
            
            row.style.display = matches ? '' : 'none';
        });
    }, 300);
});
</script>

@endsection
