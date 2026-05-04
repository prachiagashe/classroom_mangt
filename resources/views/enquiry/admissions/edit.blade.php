@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Header with Gradient Background -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Edit Admission</h1>
                <p class="text-blue-100">Update student admission information and payment details</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('enquiry.admissions.index') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur text-white px-4 py-2 rounded-lg transition-all duration-200 border border-white/30">
                    ← Back to List
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm animate-fadeIn" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm animate-fadeIn" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm animate-fadeIn">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Validation failed:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('enquiry.admissions.update', $admission->id) }}"
          method="POST"
          class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 space-y-8">

        @csrf
        @method('PUT')

        <!-- Student Information Section -->
        <div class="space-y-6">
            <div class="flex items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Student Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Student Name</label>
                    <input type="text" name="student_name"
                           value="{{ old('student_name', $admission->student_name ?? $admission->first_name . ' ' . $admission->middle_name . ' ' . $admission->surname) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter student name">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Parent Name</label>
                    <input type="text" name="parent_name"
                           value="{{ old('parent_name', $admission->parent_name ?? $admission->middle_name . ' ' . $admission->surname) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter parent name">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Class</label>
                    <input type="text" name="class"
                           value="{{ old('class', $admission->class) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter class">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Contact</label>
                    <input type="text" name="contact"
                           value="{{ old('contact', $admission->contact ?? $admission->mobile) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter contact number">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $admission->email) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter email address">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth', $admission->date_of_birth ? $admission->date_of_birth->format('Y-m-d') : '') }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Roll Number</label>
                    <input type="text" name="roll_number"
                           value="{{ old('roll_number', $admission->roll_number) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter roll number">
                </div>
            </div>
        </div>

        
        <!-- Fee Information Section -->
        <div class="space-y-6">
            <div class="flex items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Fee Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Fees</label>
                    <input type="number" name="total_fees" id="total_fees"
                           value="{{ old('total_fees', $admission->enquiry->total_fees ?? $admission->total_fee) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter total fees" oninput="calculateFinalFees()">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Discount</label>
                    <input type="number" name="discount_fees" id="discount_fees"
                           value="{{ old('discount_fees', $admission->enquiry->discount_fees ?? 0) }}"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Enter discount" oninput="calculateFinalFees()">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Final Fees</label>
                    <input type="number" name="final_fees" id="final_fees"
                           value="{{ old('final_fees', $admission->enquiry->final_fees ?? (($admission->enquiry->total_fees ?? $admission->total_fee) - ($admission->enquiry->discount_fees ?? 0))) }}"
                           readonly
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 bg-gray-50 font-semibold text-green-600">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Mode of Payment</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors duration-200 payment-option border-gray-200 hover:border-blue-500">
                    <input type="radio" name="payment_mode" value="cash" 
                           {{ old('payment_mode', $admission->payment_mode) == 'cash' ? 'checked' : '' }}
                           onchange="toggleInstallmentFields()"
                           class="mr-3 text-blue-600 focus:ring-blue-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Cash</span>
                    </div>
                </label>

                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors duration-200 payment-option border-gray-200 hover:border-blue-500">
                    <input type="radio" name="payment_mode" value="online" 
                           {{ old('payment_mode', $admission->payment_mode) == 'online' ? 'checked' : '' }}
                           onchange="toggleInstallmentFields()"
                           class="mr-3 text-blue-600 focus:ring-blue-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        <span class="font-medium">Online</span>
                    </div>
                </label>

                <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors duration-200 payment-option border-gray-200 hover:border-blue-500">
                    <input type="radio" name="payment_mode" value="installment" 
                           {{ old('payment_mode', ($admission->installment_count > 1 ? 'installment' : $admission->payment_mode)) == 'installment' ? 'checked' : '' }}
                           onchange="toggleInstallmentFields()"
                           class="mr-3 text-blue-600 focus:ring-blue-500">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span class="font-medium">Installment</span>
                    </div>
                </label>
            </div>

            <!-- One-time Payment Amount (Cash/Online) -->
            <div id="oneTimeAmountFields" class="hidden space-y-4 p-6 bg-green-50 rounded-lg border-2 border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Amount Paid (Initial)
                    </h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Specify Amount Paid</label>
                        <input type="number" name="one_time_amount" id="one_time_amount"
                               value="{{ old('one_time_amount', $admission->paid_amount) }}"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 {{ $hasPayments ? 'bg-green-50/50 font-bold' : '' }}"
                               placeholder="Enter amount" oninput="validatePaidAmount()">
                        @if($hasPayments)
                             <p class="text-[10px] text-green-600 mt-1 font-bold">Payments exist. Total recorded: ₹{{ number_format($admission->paid_amount, 2) }}</p>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Installment Fields (Hidden by Default) -->
            <div id="installmentFields" class="hidden space-y-6 p-6 bg-gray-50 rounded-lg border-2 border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Installment Details
                    </h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Number of Installments</label>
                        <input type="number" name="number_of_installments" id="number_of_installments"
                               value="{{ old('number_of_installments', $admission->installment_count) }}"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="Enter number" min="1" oninput="calculateInstallmentAmount()">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Installment Start Date</label>
                        <input type="date" name="installment_start_date"
                               value="{{ old('installment_start_date', $admission->installment_start_date) }}"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Installment Duration</label>
                        <select name="installment_duration"
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Select Duration</option>
                            <option value="monthly" {{ old('installment_duration', $admission->installment_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="weekly" {{ old('installment_duration', $admission->installment_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">First Payment Amount</label>
                        <input type="number" name="first_payment_amount" id="first_payment_amount"
                               value="{{ old('first_payment_amount', ($hasPayments ? $admission->paid_amount : 0)) }}"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 {{ $hasPayments ? 'bg-blue-50/50 font-bold' : '' }}"
                               placeholder="Enter amount" oninput="validatePaidAmount()">
                        @if($hasPayments)
                             <p class="text-[10px] text-amber-600 mt-1 font-bold">Lock enabled: Payment history verified.</p>
                        @endif
                    </div>
                </div>

                <!-- Installment Amount Display -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-600">Calculated Installment Amount</p>
                                <p class="text-xs text-gray-500">Per installment payment</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-600" id="calculatedInstallmentAmount">₹0.00</p>
                            <p class="text-xs text-gray-500" id="installmentCalculationDetails">Enter installment details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remarks Section -->
        <div class="space-y-6">
            <div class="flex items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Remarks</h3>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Additional Remarks</label>
                <textarea name="remarks"
                          class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                          rows="4"
                          placeholder="Enter any additional remarks or notes">{{ old('remarks', $admission->remarks) }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            <a href="{{ route('enquiry.admissions.show', $admission->id) }}"
               class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                ← Cancel
            </a>

            <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                Update Admission
            </button>
        </div>

    </form>
</div>

<script>
// Validate paid amount does not exceed final fees
function validatePaidAmount() {
    const finalFees = parseFloat(document.getElementById('final_fees').value) || 0;
    const oneTimeAmountInput = document.getElementById('one_time_amount');
    const firstPaymentAmountInput = document.getElementById('first_payment_amount');
    
    const oneTimeAmount = parseFloat(oneTimeAmountInput?.value) || 0;
    const firstPaymentAmount = parseFloat(firstPaymentAmountInput?.value) || 0;
    
    // Check which one is currently visible and active
    const paymentModes = document.getElementsByName('payment_mode');
    let selectedMode = '';
    for (let mode of paymentModes) {
        if (mode.checked) {
            selectedMode = mode.value;
            break;
        }
    }
    
    if (selectedMode === 'cash' || selectedMode === 'online') {
        if (oneTimeAmount > finalFees) {
            oneTimeAmountInput.classList.add('border-red-500', 'text-red-600');
            oneTimeAmountInput.classList.remove('border-gray-200', 'border-green-500');
        } else {
            oneTimeAmountInput.classList.remove('border-red-500', 'text-red-600');
            oneTimeAmountInput.classList.add('border-green-500');
        }
    } else if (selectedMode === 'installment') {
        if (firstPaymentAmount > finalFees) {
            firstPaymentAmountInput.classList.add('border-red-500', 'text-red-600');
            firstPaymentAmountInput.classList.remove('border-gray-200', 'border-blue-500');
        } else {
            firstPaymentAmountInput.classList.remove('border-red-500', 'text-red-600');
            firstPaymentAmountInput.classList.add('border-blue-500');
        }
    }
}

// Calculate final fees automatically
function calculateFinalFees() {
    const totalFees = parseFloat(document.getElementById('total_fees').value) || 0;
    const discountFees = parseFloat(document.getElementById('discount_fees').value) || 0;
    const finalFees = totalFees - discountFees;
    document.getElementById('final_fees').value = finalFees >= 0 ? finalFees : 0;
    
    if (finalFees < 0) {
        document.getElementById('final_fees').classList.add('text-red-600');
    } else {
        document.getElementById('final_fees').classList.remove('text-red-600');
    }

    // Recalculate installment amount and validate amounts
    calculateInstallmentAmount();
    validatePaidAmount();
}

// Calculate installment amount automatically
function calculateInstallmentAmount() {
    const finalFees = parseFloat(document.getElementById('final_fees').value) || 0;
    const numberOfInstallments = parseInt(document.getElementById('number_of_installments')?.value) || 0;
    const installmentAmountElement = document.getElementById('calculatedInstallmentAmount');
    const installmentDetailsElement = document.getElementById('installmentCalculationDetails');
    
    if (numberOfInstallments > 0 && finalFees > 0) {
        const installmentAmount = finalFees / numberOfInstallments;
        installmentAmountElement.textContent = '₹' + installmentAmount.toFixed(2);
        installmentDetailsElement.textContent = `${finalFees.toFixed(2)} ÷ ${numberOfInstallments} installments`;
    } else {
        installmentAmountElement.textContent = '₹0.00';
        installmentDetailsElement.textContent = 'Enter installment details';
    }
}

// Toggle installment fields based on payment mode
function toggleInstallmentFields() {
    const paymentModes = document.getElementsByName('payment_mode');
    const installmentFields = document.getElementById('installmentFields');
    const oneTimeAmountFields = document.getElementById('oneTimeAmountFields');
    
    let selectedMode = '';
    for (let mode of paymentModes) {
        if (mode.checked) {
            selectedMode = mode.value;
            break;
        }
    }
    
    if (selectedMode === 'installment') {
        installmentFields.classList.remove('hidden');
        installmentFields.classList.add('animate-fadeIn');
        oneTimeAmountFields?.classList.add('hidden');
        // Calculate installment amount when fields are shown
        setTimeout(() => calculateInstallmentAmount(), 100);
    } else if (selectedMode === 'cash' || selectedMode === 'online') {
        installmentFields.classList.add('hidden');
        if (oneTimeAmountFields) {
            oneTimeAmountFields.classList.remove('hidden');
            oneTimeAmountFields.classList.add('animate-fadeIn');
            
            // Default amount to final fees if it's the first time and amount is 0/empty
            const amountInput = document.getElementById('one_time_amount');
            if (amountInput && (!amountInput.value || amountInput.value == 0)) {
                const finalFees = parseFloat(document.getElementById('final_fees').value) || 0;
                amountInput.value = finalFees;
            }
        }
    } else {
        installmentFields.classList.add('hidden');
        oneTimeAmountFields?.classList.add('hidden');
    }
    
    // Update visual state of payment options
    document.querySelectorAll('.payment-option').forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio.checked) {
            option.classList.add('border-blue-500', 'bg-blue-50');
        } else {
            option.classList.remove('border-blue-500', 'bg-blue-50');
        }
    });

    // Validate paid amount in new mode
    validatePaidAmount();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateFinalFees();
    toggleInstallmentFields();
    
    // Add event listeners for real-time calculations
    const totalFeesInput = document.getElementById('total_fees');
    const discountFeesInput = document.getElementById('discount_fees');
    const numberOfInstallmentsInput = document.getElementById('number_of_installments');
    
    if (totalFeesInput) totalFeesInput.addEventListener('input', calculateFinalFees);
    if (discountFeesInput) discountFeesInput.addEventListener('input', calculateFinalFees);
    if (numberOfInstallmentsInput) numberOfInstallmentsInput.addEventListener('input', calculateInstallmentAmount);
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}
</style>

@endsection
