@extends('layouts.app')

@section('title', 'Edit Subject - ' . $subject->name)

@section('page-title', 'Edit Subject')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('admin.subjects.class', $subject->class_name) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Class {{ $subject->class_name }}
            </a>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Subject</h1>
        <p class="text-gray-600">Update subject information for {{ $subject->name }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ $subject->name }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="code" name="code" value="{{ $subject->code }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="course_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Course <span class="text-red-500">*</span>
                            </label>
                            <select id="course_name" name="course_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="REGULAR" {{ $subject->course_name == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                <option value="NEET" {{ $subject->course_name == 'NEET' ? 'selected' : '' }}>NEET</option>
                                <option value="JEE" {{ $subject->course_name == 'JEE' ? 'selected' : '' }}>JEE</option>
                                <option value="MHT-CET" {{ $subject->course_name == 'MHT-CET' ? 'selected' : '' }}>MHT-CET</option>
                            </select>
                        </div>
                        <div>
                            <label for="program_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Program <span class="text-red-500">*</span>
                            </label>
                            <select id="program_type" name="program_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="Regular" {{ $subject->program_type == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Repeater" {{ $subject->program_type == 'Repeater' ? 'selected' : '' }}>Repeater</option>
                                <option value="Crash Course" {{ $subject->program_type == 'Crash Course' ? 'selected' : '' }}>Crash Course</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="teacher_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Teacher Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="teacher_name" name="teacher_name" value="{{ $subject->teacher_name }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('teacher_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="teacher_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Teacher Email
                        </label>
                        <input type="email" id="teacher_email" name="teacher_email" value="{{ $subject->teacher_email }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('teacher_email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- <div>
                        <label for="credits" class="block text-sm font-medium text-gray-700 mb-2">
                            Credits <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="credits" name="credits" min="1" max="10" value="{{ $subject->credits }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('credits')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div> -->

                    <div>
                        <label class="flex items-center mt-6">
                            <input type="checkbox" name="is_active" value="1" {{ $subject->is_active ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Active Subject</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $subject->description ?? '' }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <a href="{{ route('admin.subjects.class', $subject->class_name) }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
