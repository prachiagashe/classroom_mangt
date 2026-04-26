@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Calling Data</h2>
            <p class="text-sm text-gray-500">Update calling details</p>
        </div>
        <a href="{{ route('calling.index') }}" 
           class="text-sm text-blue-600 hover:underline">
            ← Back to List
        </a>
    </div>

    <!-- Card -->
    <div class="bg-white shadow-md rounded-xl p-6">

        <form action="{{ route('calling.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- BASIC INFO -->
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Basic Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                <div>
                    <label class="text-sm text-gray-600">Sr No</label>
                    <input type="text" value="{{ $data->sr_no }}" 
                           class="w-full mt-1 p-2 border rounded bg-gray-100" readonly>
                </div>

                <div>
                    <label class="text-sm text-gray-600">School Name <span class="text-red-500">*</span></label>
                    <input type="text" name="school_name" value="{{ old('school_name', $data->school_name) }}" 
                           class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400"
                           required
                           pattern="[A-Za-z\s\.]+"
                           onkeypress="onlyTextInput(event)">
                    @error('school_name')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Student Name <span class="text-red-500">*</span></label>
                    <input type="text" name="student_name" value="{{ old('student_name', $data->student_name) }}" 
                           class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400"
                           required
                           pattern="[A-Za-z\s]+"
                           onkeypress="onlyTextInput(event)">
                    @error('student_name')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile_no" value="{{ old('mobile_no', $data->mobile_no) }}" 
                           class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400">
                    @error('mobile_no')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <!-- CALL INFO -->
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Calling Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                <div>
                    <label class="text-sm text-gray-600">Response <span class="text-red-500">*</span></label>
                    <select name="response" class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" required>
                        <option value="" disabled {{ !$data->response ? 'selected' : '' }}>Select Response</option>
                        <option value="Positive" {{ old('response', $data->response) == 'Positive' ? 'selected' : '' }}>Positive</option>
                        <option value="Negative" {{ old('response', $data->response) == 'Negative' ? 'selected' : '' }}>Negative</option>
                        <option value="Pending" {{ old('response', $data->response) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('response')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Call Status <span class="text-red-500">*</span></label>
                    <select name="call_status" class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" required>
                        <option value="" disabled {{ !$data->call_status ? 'selected' : '' }}>Select Status</option>
                        <option value="Done" {{ old('call_status', $data->call_status) == 'Done' ? 'selected' : '' }}">Done</option>
                        <option value="Not Received" {{ old('call_status', $data->call_status) == 'Not Received' ? 'selected' : '' }}">Not Received</option>
                        <option value="Pending" {{ old('call_status', $data->call_status) == 'Pending' ? 'selected' : '' }}">Pending</option>
                    </select>
                    @error('call_status')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="visit_branch" value="1"
                                {{ old('visit_branch', $data->visit_branch) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Visit Branch</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="follow_up" value="1"
                                {{ old('follow_up', $data->follow_up) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Follow-up Required</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('calling.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                    Cancel
                </a>

                <button type="submit" 
                        class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow transition-colors">
                    Update Data
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
