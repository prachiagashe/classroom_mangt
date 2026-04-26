@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Class Schedule</h1>
        <a href="{{ route('teacher.assignments') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Schedule
        </a>
    </div>

    <!-- Schedule Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Schedule New Class</h2>
            <p class="text-sm text-gray-600 mt-1">Add a new class to your teaching schedule</p>
        </div>
        
        <form class="p-6" action="{{ route('teacher.schedule.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Subject Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                        <select name="subject" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Subject</option>
                            @if($employee->assigned_subjects)
                                @foreach(explode(', ', $employee->assigned_subjects) as $subject)
                                    <option value="{{ $subject }}" {{ old('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class <span class="text-red-500">*</span></label>
                        <select name="class" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Class</option>
                            @if($employee->assigned_classes)
                                @foreach(explode(', ', $employee->assigned_classes) as $class)
                                    <option value="{{ $class }}" {{ old('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('class')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" required 
                               value="{{ old('date') }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Number <span class="text-red-500">*</span></label>
                        <input type="text" name="room" required 
                               value="{{ old('room') }}"
                               placeholder="e.g., Room 201, Lab 102"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('room')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" required 
                               value="{{ old('start_time') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" required 
                               value="{{ old('end_time') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class Type <span class="text-red-500">*</span></label>
                        <select name="class_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Type</option>
                            <option value="lecture" {{ old('class_type') == 'lecture' ? 'selected' : '' }}>Lecture</option>
                            <option value="lab" {{ old('class_type') == 'lab' ? 'selected' : '' }}>Lab Session</option>
                            <option value="tutorial" {{ old('class_type') == 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                            <option value="exam" {{ old('class_type') == 'exam' ? 'selected' : '' }}>Exam/Test</option>
                            <option value="assignment" {{ old('class_type') == 'assignment' ? 'selected' : '' }}>Assignment Review</option>
                        </select>
                        @error('class_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recurring Pattern -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recurring Pattern</label>
                        <select name="recurring" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="none" {{ old('recurring') == 'none' ? 'selected' : '' }}>One-time Class</option>
                            <option value="daily" {{ old('recurring') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('recurring') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('recurring') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                        @error('recurring')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea name="notes" rows="3" 
                          placeholder="Any additional information about this class..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('teacher.assignments') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium">
                    Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
