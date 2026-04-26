@extends('layouts.app')

@section('title', 'My Courses')

@section('page-title', 'My Courses')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Courses</h1>
        <div class="flex items-center gap-4">
            @if($studentClass)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    Class {{ $studentClass }}
                </span>
            @endif
            @if($courseType)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $courseType == 'NEET' ? 'bg-purple-100 text-purple-800' : 
                       ($courseType == 'JEE' ? 'bg-indigo-100 text-indigo-800' : 
                       ($courseType == 'MHT-CET' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ $courseType }}
                </span>
            @endif
            <p class="text-gray-600">View and manage your enrolled courses.</p>
        </div>
    </div>

    @if($subjects->count() > 0)
        {{-- Group subjects by course type --}}
        @php
            $subjectsByType = $subjects->groupBy('course_type');
            $courseTypeOrder = ['REGULAR', 'NEET', 'JEE', 'MHT-CET'];
        @endphp

        @foreach($courseTypeOrder as $courseType)
            @if($subjectsByType->has($courseType) && $subjectsByType->get($courseType)->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 mr-3">
                            {{ $courseType }} Subjects
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $courseType == 'NEET' ? 'bg-purple-100 text-purple-800' : 
                               ($courseType == 'JEE' ? 'bg-indigo-100 text-indigo-800' : 
                               ($courseType == 'MHT-CET' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $subjectsByType->get($courseType)->count() }} subjects
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($subjectsByType->get($courseType) as $subject)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        @if($subject->name == 'Mathematics')
                                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        @elseif($subject->name == 'Science' || $subject->name == 'Physics' || $subject->name == 'Chemistry' || $subject->name == 'Biology')
                                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                        @elseif($subject->name == 'English' || $subject->name == 'Hindi' || $subject->name == 'Marathi')
                                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $subject->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $subject->code }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $subject->teacher_name }}
                                    </div>
                                    
                                    @if($subject->teacher_email)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $subject->teacher_email }}
                                        </div>
                                    @endif
                                    
                                    <!-- <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $subject->credits }} credits
                                    </div> -->
                                </div>
                                
                                @if($subject->description)
                                    <p class="mt-3 text-sm text-gray-600">{{ $subject->description }}</p>
                                @endif
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $subject->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Details →
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No courses found</h3>
                <p class="text-gray-500">No courses have been assigned to your class yet.</p>
            </div>
        </div>
    @endif
</div>
@endsection
