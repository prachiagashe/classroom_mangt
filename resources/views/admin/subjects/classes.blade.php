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
                    @if(in_array($classData['name'], ['11', '12']))
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Class {{ $classData['name'] }}</h3>
                        <p class="text-sm text-gray-500 mb-3">
                            {{ $classData['subject_count'] }} {{ $classData['subject_count'] == 1 ? 'subject' : 'subjects' }}
                        </p>
                        
                        <div class="space-y-2">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                </svg>
                                NEET
                            </div>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                </svg>
                                JEE
                            </div>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                </svg>
                                MHT-CET
                            </div>
                        </div>
                    @else
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Class {{ $classData['name'] }}</h3>
                        <p class="text-sm text-gray-500 mb-3">
                            {{ $classData['subject_count'] }} {{ $classData['subject_count'] == 1 ? 'subject' : 'subjects' }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Repeater Programs Section -->
    <!-- <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Repeater Programs</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach($classes as $classData)
                @if(isset($classData['is_repeater']) && $classData['is_repeater'])
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer p-6"
                         onclick="window.location.href='{{ route('admin.subjects.class', $classData['name']) }}'">
                        <div class="text-center">
                            @if($classData['name'] === 'NEET Repeater')
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $classData['name'] }}</h3>
                            <p class="text-sm text-gray-500 mb-3">
                                {{ $classData['name'] === 'NEET Repeater' ? 'Medical Entrance Preparation' : 'Engineering Entrance Preparation' }}
                            </p>
                            
                            <div class="space-y-2">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                    </svg>
                                    {{ $classData['subject_count'] }} {{ $classData['subject_count'] == 1 ? 'subject' : 'subjects' }}
                                </div>
                                @if($classData['name'] === 'NEET Repeater')
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                        </svg>
                                        Biology, Physics, Chemistry
                                    </div>
                                @else
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                        </svg>
                                        Physics, Chemistry, Math
                                    </div>
                                @endif
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                    </svg>
                                    Mock Tests
                                </div>
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-2.819 2.82a1 1 0 00-.394.025L1.839 15.19a1 1 0 00-.117.642l.001.007.117.641.025.007.117.642.005.003.018.009.004.014.018.004.005.003.014.003.014.002.004.002.01.003.011.002.009.002.006.003.005.003.005.003.005.002.009.004.008.002.006.004"/>
                                    </svg>
                                    Study Materials
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div> -->
</div>
@endsection
