@extends('layouts.app')

@section('title', 'Add New Calling Entry')

@section('content')
<div class="max-w-7xl mx-auto mt-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Add New Calling Entry</h1>
                <p class="text-gray-600 mt-1">Fill in the details to add a new calling record</p>
            </div>
            <a href="{{ route('calling.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>

        <!-- Form Section -->
        <div class="bg-gray-50 rounded-lg p-6">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('calling.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-700">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <strong>Note:</strong> Sr No will be automatically generated (1,2,3,4...)
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Name -->
                    <div>
                        <label for="school_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            School Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('school_name') border-red-500 @enderror" 
                                   id="school_name" 
                                   name="school_name" 
                                   value="{{ old('school_name') }}" 
                                   required
                                   pattern="[A-Za-z\s\.]+"
                                   onkeypress="onlyTextInput(event)">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        @error('school_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Name -->
                    <div>
                        <label for="student_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Student Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_name') border-red-500 @enderror" 
                                   id="student_name" 
                                   name="student_name" 
                                   value="{{ old('student_name') }}" 
                                   required
                                   pattern="[A-Za-z\s]+"
                                   onkeypress="onlyTextInput(event)">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('student_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mobile No -->
                    <div>
                        <label for="mobile_no" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mobile No <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mobile_no') border-red-500 @enderror" 
                                   id="mobile_no" 
                                   name="mobile_no" 
                                   value="{{ old('mobile_no') }}" 
                                   maxlength="10" 
                                   pattern="[6-9][0-9]{9}" 
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('mobile_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Enter 10-digit mobile number starting with 6-9</p>
                    </div>

                    <!-- Response -->
                    <div>
                        <label for="response" class="block text-sm font-semibold text-gray-700 mb-2">
                            Response <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('response') border-red-500 @enderror appearance-none" 
                                    id="response" 
                                    name="response" 
                                    required>
                                <option value="" disabled selected>Select Response</option>
                                <option value="Positive" {{ old('response') == 'Positive' ? 'selected' : '' }}>
                                    Positive
                                </option>
                                <option value="Negative" {{ old('response') == 'Negative' ? 'selected' : '' }}>
                                    Negative
                                </option>
                                <option value="Pending" {{ old('response') == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        @error('response')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Call Status -->
                    <div>
                        <label for="call_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Call Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('call_status') border-red-500 @enderror appearance-none" 
                                    id="call_status" 
                                    name="call_status" 
                                    required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Done" {{ old('call_status') == 'Done' ? 'selected' : '' }}>
                                    Done
                                </option>
                                <option value="Not Received" {{ old('call_status') == 'Not Received' ? 'selected' : '' }}>
                                    Not Received
                                </option>
                                <option value="Pending" {{ old('call_status') == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        @error('call_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visit Branch -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Visit Branch
                        </label>
                        <div class="flex items-center h-10">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                   id="visit_branch" 
                                   name="visit_branch" 
                                   value="1"
                                   {{ old('visit_branch') ? 'checked' : '' }}>
                            <label for="visit_branch" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                Student visited the branch
                            </label>
                        </div>
                    </div>

                    <!-- Follow-up -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Follow-up Required
                        </label>
                        <div class="flex items-center h-10">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                   id="follow_up" 
                                   name="follow_up" 
                                   value="1"
                                   onchange="toggleFollowUpDate()"
                                   {{ old('follow_up') ? 'checked' : '' }}>
                            <label for="follow_up" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                Follow-up call required
                            </label>
                        </div>
                    </div>

                    <!-- Follow-up Date (shown only when follow_up is checked) -->
                    <div id="follow_up_date_container" class="hidden">
                        <label for="follow_up_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Follow-up Date <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               id="follow_up_date" 
                               name="follow_up_date" 
                               value="{{ old('follow_up_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               {{ old('follow_up') ? '' : 'disabled' }}>
                        @error('follow_up_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('calling.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Mobile number validation
document.getElementById('mobile_no').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 10) {
        value = value.slice(0, 10);
    }
    e.target.value = value;
});

// Prevent non-numeric input for mobile
document.getElementById('mobile_no').addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
        e.preventDefault();
    }
});

// Toggle follow-up date visibility
function toggleFollowUpDate() {
    const checkbox = document.getElementById('follow_up');
    const dateContainer = document.getElementById('follow_up_date_container');
    const dateInput = document.getElementById('follow_up_date');
    
    if (checkbox.checked) {
        dateContainer.classList.remove('hidden');
        dateInput.disabled = false;
    } else {
        dateContainer.classList.add('hidden');
        dateInput.disabled = true;
        dateInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFollowUpDate();
});

function onlyTextInput(event) {
    let char = String.fromCharCode(event.which);
    if (!/[A-Za-z\s]/.test(char)) {
        event.preventDefault();
    }
}
</script>
@endsection
