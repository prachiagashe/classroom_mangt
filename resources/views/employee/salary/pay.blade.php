@extends('layouts.app')

@section('title', 'Pay Salary')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Pay Salary</h1>
            <a href="{{ route('salary.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Salary Management
            </a>
        </div>

        <!-- Employee Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $salaryRecord->employee->full_name }}</h2>
                    <p class="text-gray-600">{{ $salaryRecord->employee->employee_code }}</p>
                    <p class="text-gray-600">{{ $salaryRecord->employee->department }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Salary Period</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ date('F Y', mktime(0, 0, 0, $salaryRecord->month, 1, $salaryRecord->year)) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('salary.update', $salaryRecord->id) }}" id="paymentForm">
                @csrf
                @method('PUT')
                
                <!-- Salary Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                            ₹{{ number_format($salaryRecord->basic_salary, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Net Salary</label>
                        <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg" id="netSalaryDisplay">
                            ₹{{ number_format($salaryRecord->net_salary, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="deduction_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Deduction Amount
                        </label>
                        <input type="number" 
                               id="deduction_amount"
                               name="deduction_amount" 
                               step="0.01"
                               min="0"
                               value="{{ $salaryRecord->deduction_amount ?? 0 }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               oninput="calculateNetSalary()">
                    </div>
                    <div>
                        <label for="bonus_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Bonus Amount
                        </label>
                        <input type="number" 
                               id="bonus_amount"
                               name="bonus_amount" 
                               step="0.01"
                               min="0"
                               value="{{ $salaryRecord->bonus_amount ?? 0 }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               oninput="calculateNetSalary()">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Paid Amount <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="paid_amount"
                               name="paid_amount" 
                               step="0.01"
                               min="1"
                               value="{{ $salaryRecord->paid_amount ?? 0 }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               oninput="updatePaymentStatus()">
                        @error('paid_amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="payment_date"
                               name="payment_date" 
                               value="{{ $salaryRecord->payment_date ? date('Y-m-d', strtotime($salaryRecord->payment_date)) : date('Y-m-d') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method
                    </label>
                    <select id="payment_method" 
                            name="payment_method" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Select Payment Method</option>
                        <option value="bank_transfer" {{ $salaryRecord->payment_method == 'bank_transfer' ? 'selected' : '' }}>
                            Bank Transfer
                        </option>
                        <option value="cash" {{ $salaryRecord->payment_method == 'cash' ? 'selected' : '' }}>
                            Cash
                        </option>
                        <option value="upi" {{ $salaryRecord->payment_method == 'upi' ? 'selected' : '' }}>
                            UPI
                        </option>
                        <option value="cheque" {{ $salaryRecord->payment_method == 'cheque' ? 'selected' : '' }}>
                            Cheque
                        </option>
                    </select>
                    @error('payment_method')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                        Remarks
                    </label>
                    <textarea id="remarks" 
                              name="remarks" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $salaryRecord->remarks ?? '' }}</textarea>
                </div>

                <!-- Payment Status Display -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                        <span id="paymentStatus" class="px-3 py-1 text-sm font-semibold rounded-full">
                            @switch($salaryRecord->payment_status)
                                @case('pending')
                                    <span class="bg-yellow-100 text-yellow-800">Pending</span>
                                    @break
                                @case('partial')
                                    <span class="bg-orange-100 text-orange-800">Partial</span>
                                    @break
                                @case('paid')
                                    <span class="bg-green-100 text-green-800">Paid</span>
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Update Payment
                    </button>
                    <a href="{{ route('salary.index') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateNetSalary() {
    const basicSalary = {{ $salaryRecord->basic_salary }};
    const deduction = parseFloat(document.getElementById('deduction_amount').value) || 0;
    const bonus = parseFloat(document.getElementById('bonus_amount').value) || 0;
    const netSalary = basicSalary - deduction + bonus;
    
    document.getElementById('netSalaryDisplay').textContent = '₹' + netSalary.toFixed(2);
    updatePaymentStatus();
}

function updatePaymentStatus() {
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const netSalaryText = document.getElementById('netSalaryDisplay').textContent;
    const netSalary = parseFloat(netSalaryText.replace('₹', '').replace(',', ''));
    
    const statusElement = document.getElementById('paymentStatus');
    
    if (paidAmount === 0) {
        statusElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800';
        statusElement.textContent = 'Pending';
    } else if (paidAmount < netSalary) {
        statusElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800';
        statusElement.textContent = 'Partial';
    } else {
        statusElement.className = 'px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800';
        statusElement.textContent = 'Paid';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateNetSalary();
});

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async response => {
        if(response.ok) {
            Swal.fire({
                title: 'Payment Updated Successfully',
                text: 'Salary payment details have been updated successfully.',
                icon: 'success',
                timer: 5000,
                timerProgressBar: true,
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonColor: '#16a34a',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'px-6 py-2.5 rounded-lg font-medium'
                }
            }).then(() => {
                window.location.href = response.url;
            });
        } else {
            Swal.fire({
                title: 'Payment Update Failed',
                text: 'Something went wrong. Please try again.',
                icon: 'error',
                timer: 5000,
                timerProgressBar: true,
                showCloseButton: true,
                showConfirmButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Try Again',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'px-6 py-2.5 rounded-lg font-medium'
                }
            });
            btn.disabled = false;
            btn.innerText = originalText;
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Payment Update Failed',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            timer: 5000,
            timerProgressBar: true,
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Try Again',
            customClass: {
                popup: 'rounded-2xl shadow-2xl',
                confirmButton: 'px-6 py-2.5 rounded-lg font-medium'
            }
        });
        btn.disabled = false;
        btn.innerText = originalText;
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
