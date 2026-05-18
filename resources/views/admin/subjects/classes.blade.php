@extends('layouts.app')

@section('title', 'Subject Management')

@section('page-title', 'Subject Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Subject Management</h1>
            <p class="text-gray-500">Manage subjects and student requests for all classes. Click on a class to view details.</p>
        </div>
        <div class="bg-blue-600 p-4 rounded-xl shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($classes as $classData)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer p-6"
                 onclick="window.location.href='{{ route('admin.subjects.class', $classData['name']) }}'">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 {{ in_array($classData['name'], ['11', '12']) ? 'bg-orange-100' : 'bg-blue-100' }} rounded-full mb-4">
                        <svg class="w-8 h-8 {{ in_array($classData['name'], ['11', '12']) ? 'text-orange-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Class {{ $classData['name'] }}</h3>
                    <p class="text-sm text-gray-500 mb-3">
                        {{ $classData['subject_count'] }} {{ $classData['subject_count'] == 1 ? 'subject' : 'subjects' }}
                    </p>
                    
                    @if(in_array($classData['name'], ['11', '12']))
                        <div class="flex flex-wrap justify-center gap-2 mt-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100">NEET</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-50 text-green-700 border border-green-100">JEE</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-purple-50 text-purple-700 border border-purple-100">CET</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Specialized & Competitive Programs -->
    @if(count($specialPrograms) > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.727 2.903a2 2 0 01-3.464 0L9.141 15.82a2 2 0 00-1.96-1.414l-2.387.477a2 2 0 00-1.022.547m0 0l-1.428 1.428a2 2 0 01-3.414-1.414l.707-2.828a2 2 0 00-.547-1.022L.543 10.141a2 2 0 010-3.414l2.828-.707a2 2 0 001.022-.547l1.428-1.428a2 2 0 013.414 0l.707 2.828a2 2 0 00.547 1.022l1.428 1.428a2 2 0 010 3.414l-2.828.707a2 2 0 00-1.022.547l-1.428 1.428a2 2 0 01-3.414 0l-.707-2.828a2 2 0 00-.547-1.022l-1.428-1.428a2 2 0 010-3.414l2.828-.707a2 2 0 001.022-.547l1.428-1.428a2 2 0 013.414 0l.707 2.828a2 2 0 00.547 1.022l1.428 1.428a2 2 0 010 3.414l-2.828.707a2 2 0 00-1.022.547l-1.428 1.428z"/>
                    </svg>
                </span>
                Specialized & Competitive Programs
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                @foreach($specialPrograms as $program)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all cursor-pointer overflow-hidden group"
                         onclick="window.location.href='{{ route('admin.subjects.class', $program['name']) }}'">
                        <div class="h-2 bg-gradient-to-r {{ $program['course_name'] === 'NEET' ? 'from-red-500 to-pink-500' : ($program['course_name'] === 'JEE' ? 'from-blue-500 to-indigo-500' : 'from-purple-500 to-indigo-500') }}"></div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 rounded-lg {{ $program['course_name'] === 'NEET' ? 'bg-red-50 text-red-600' : ($program['course_name'] === 'JEE' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600') }}">
                                    @if($program['course_name'] === 'NEET')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    @endif
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $program['program_type'] === 'Repeater' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $program['program_type'] }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $program['name'] }}</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                {{ $program['course_name'] }} entrance preparation for {{ strtolower($program['program_type']) }} students.
                            </p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                <div class="flex items-center text-sm font-medium text-gray-600">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $program['subject_count'] }} Subjects
                                </div>
                                <span class="text-indigo-600 text-sm font-bold group-hover:translate-x-1 transition-transform inline-flex items-center">
                                    View Subjects
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
