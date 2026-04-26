@extends('layouts.app')

@section('title', 'Create Assignment')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Assignment</h1>
        <a href="{{ route('teacher.dashboard') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Dashboard
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">📝 Assignment Details</h2>
                <p class="text-gray-600 mt-1">Create a new assignment for your students.</p>
            </div>
            
            <form method="POST" action="{{ route('teacher.assignments.store') }}" class="p-6">
                @csrf
                
                <!-- Class Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                    <select name="class" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                    <select name="section" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}">{{ $section }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <select name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assignment Title *</label>
                    <input type="text" name="title" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="e.g., Physics Chapter 5 Assignment">
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Provide detailed instructions for the assignment..."></textarea>
                </div>

                <!-- Due Date -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Attachment -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attachment (Optional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <div class="mt-4">
                            <label for="attachment" class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                    Click to upload or drag and drop
                                </span>
                                <span class="mt-1 block text-xs text-gray-500">
                                    PDF, DOC, DOCX up to 10MB
                                </span>
                            </label>
                            <input id="attachment" name="attachment" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="active" checked class="mr-2">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="closed" class="mr-2">
                            <span class="text-sm text-gray-700">Closed</span>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                        Create Assignment
                    </button>
                    <a href="{{ route('teacher.dashboard') }}" 
                       class="flex-1 bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors text-center block">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
