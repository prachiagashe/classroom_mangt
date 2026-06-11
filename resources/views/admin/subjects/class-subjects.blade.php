@extends('layouts.app')

@section('title', 'Class Subjects - ' . $className)

@section('page-title', 'Class ' . $className . ' - Subjects')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Class {{ $className }} - Subjects</h1>
            <p class="text-gray-500">Manage subjects and review student subject requests for this class.</p>
        </div>
        <div class="flex gap-3 items-center">
            <a href="{{ route('admin.subjects.classes') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-all duration-200 flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <button onclick="openDoubtSessionModal('{{ $className }}')" 
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Doubt Session
            </button>
            <button onclick="openAddSubjectModal('{{ $className }}')" 
                    class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Subject
            </button>
            <div class="bg-blue-600 p-4 rounded-xl shadow-md ml-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Course Type Filter -->
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm font-medium text-gray-700">Filter by Course Type:</span>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterSubjects('all')" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-blue-600 text-white"
                            data-filter="all">
                        All Subjects
                        <span class="ml-1 bg-white bg-opacity-20 px-2 py-0.5 rounded-full text-xs">
                            {{ $subjects->count() }}
                        </span>
                    </button>
                    <button onclick="filterSubjects('Regular')" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                            data-filter="Regular">
                        Regular
                        <span class="ml-1 bg-gray-500 bg-opacity-20 px-2 py-0.5 rounded-full text-xs">
                            {{ $subjects->where('program_type', 'Regular')->count() }}
                        </span>
                    </button>
                    {{--
                    <button onclick="filterSubjects('NEET')" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                            data-filter="NEET">
                        NEET
                        <span class="ml-1 bg-gray-500 bg-opacity-20 px-2 py-0.5 rounded-full text-xs">
                            {{ $subjects->where('course_name', 'NEET')->count() }}
                        </span>
                    </button>
                    <button onclick="filterSubjects('JEE')" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                            data-filter="JEE">
                        JEE
                        <span class="ml-1 bg-gray-500 bg-opacity-20 px-2 py-0.5 rounded-full text-xs">
                            {{ $subjects->where('course_name', 'JEE')->count() }}
                        </span>
                    </button>
                    <button onclick="filterSubjects('Repeater')" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                            data-filter="Repeater">
                        Repeater
                        <span class="ml-1 bg-gray-500 bg-opacity-20 px-2 py-0.5 rounded-full text-xs">
                            {{ $subjects->where('program_type', 'Repeater')->count() }}
                        </span>
                    </button>
                    <button onclick="filterSubjects('Crash Course')" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300"
                            data-filter="Crash Course">
                        Crash Course
                        <span class="ml-1 bg-gray-500 bg-opacity-20 px-2 py-0.5 rounded-full text-xs">
                            {{ $subjects->where('program_type', 'Crash Course')->count() }}
                        </span>
                    </button>
                    --}}
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="showTab('subjects')" id="subjects-tab" 
                        class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Subjects List
                        <span class="ml-2 bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $subjects->count() }}
                        </span>
                    </div>
                </button>
                <button onclick="showTab('timetable')" id="timetable-tab" 
                        class="tab-button py-4 px-6 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Timetable
                    </div>
                </button>
            </nav>
        </div>

        <div class="p-6">
            <div id="subjects-content" class="tab-content">
                <div class="flex justify-end mb-4">
                    <button onclick="publishSubjects()" class="bg-indigo-600 text-white px-5 py-2 rounded-xl shadow hover:bg-indigo-700 transition-colors flex items-center gap-2 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Publish Subjects
                    </button>
                </div>
                @if($subjects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subject Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Code
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Course Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Teacher
                                    </th>
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Credits
                                    </th> -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subjects as $subject)
                                    <tr class="hover:bg-gray-50 transition-colors subject-row" 
                                        data-course-name="{{ $subject->course_name }}"
                                        data-program-type="{{ $subject->program_type }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                            @if($subject->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($subject->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $subject->code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                    {{ $subject->course_name == 'NEET' ? 'bg-red-100 text-red-700' : 
                                                       ($subject->course_name == 'JEE' ? 'bg-blue-100 text-blue-700' : 
                                                       ($subject->course_name == 'MHT-CET' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')) }}">
                                                    {{ $subject->course_name ?: 'REGULAR' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $subject->teacher_name }}</div>
                                            @if($subject->teacher_email)
                                                <div class="text-sm text-gray-500">{{ $subject->teacher_email }}</div>
                                            @endif
                                        </td>
                                        <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subject->credits }}
                                        </td> -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($subject->is_active)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.subjects.edit', $subject->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this subject?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No subjects found</h3>
                        <p class="text-gray-500 mb-4">Get started by adding a new subject for this class.</p>
                        <button onclick="openAddSubjectModal('{{ $className }}')" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                            Add First Subject
                        </button>
                    </div>
                @endif
            </div>

            <!-- Timetable Tab Content -->
            <div id="timetable-content" class="tab-content hidden">
                <!-- Timetable Configuration -->
                <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Timetable Configuration</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="numberOfPeriods" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Periods per Day
                            </label>
                            <select id="numberOfPeriods" name="numberOfPeriods" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    onchange="generateTimetable()">
                                @php
                                    $currentPeriodCount = $subjects->count();
                                @endphp
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $i == $currentPeriodCount ? 'selected' : '' }}>{{ $i }} Periods</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="numberOfDays" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Working Days per Week
                            </label>
                            <select id="numberOfDays" name="numberOfDays" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    onchange="generateTimetable()">
                                <option value="5" selected>5 Days (Monday - Friday)</option>
                                <option value="6">6 Days (Monday - Saturday)</option>
                                <option value="4">4 Days (Monday - Thursday)</option>
                                <option value="3">3 Days (Monday - Wednesday)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="generateTimetable()" 
                                class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Generate Timetable
                        </button>
                    </div>
                </div>

                <!-- Period Timings Section -->
                <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Period Timings</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3" id="period-timings-container">
                        <!-- Period timing inputs will be dynamically generated here -->
                    </div>
                </div>

                <!-- Timetable Grid -->
                <div class="mb-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200" id="timetable-table">
                            <thead class="bg-gray-50" id="timetable-header">
                                <tr>
                                    <th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-900">Day / Period</th>
                                    <!-- Period headers will be dynamically generated here -->
                                </tr>
                            </thead>
                            <tbody class="bg-white" id="timetable-body">
                                <!-- Day rows will be dynamically generated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button onclick="saveTimetable()" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002 2v4a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"/>
                        </svg>
                        Save Timetable
                    </button>
                    <button onclick="publishTimetable()" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002 2v4a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V2"/>
                        </svg>
                        Publish Timetable
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div id="addSubjectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Add New Subject</h3>
            <form action="{{ route('admin.subjects.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="class_name" id="modalClassName" value="">
                
                <div class="mb-4">
                    <label for="subject_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="subject_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject_code" name="code" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="course_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Course <span class="text-red-500">*</span>
                        </label>
                        <select id="course_name" name="course_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="REGULAR">Regular</option>
                            <option value="NEET">NEET</option>
                            <option value="JEE">JEE</option>
                            <option value="MHT-CET">MHT-CET</option>
                        </select>
                    </div>
                    <div>
                        <label for="program_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Program <span class="text-red-500">*</span>
                        </label>
                        <select id="program_type" name="program_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="Regular">Regular</option>
                            <option value="Repeater">Repeater</option>
                            <option value="Crash Course">Crash Course</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="teacher_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Teacher Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="teacher_name" name="teacher_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('teacher_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="teacher_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Teacher Email
                    </label>
                    <input type="email" id="teacher_email" name="teacher_email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('teacher_email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
<!-- 
                <div class="mb-4">
                    <label for="credits" class="block text-sm font-medium text-gray-700 mb-2">
                        Credits <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="credits" name="credits" min="1" max="10" value="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('credits')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> -->

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active Subject</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddSubjectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Doubt Session Modal -->
<div id="doubtSessionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Create Doubt Session - Class {{ $className }}</h3>
                <button onclick="closeDoubtSessionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="doubtSessionForm" class="space-y-4" onsubmit="publishDoubtSession(event)">
                @csrf
                <input type="hidden" name="class_name" value="{{ $className }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <select id="subject_id" name="subject_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Loading subjects...</option>
                        </select>
                    </div>

                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Teacher <span class="text-red-500">*</span>
                        </label>
                        <select id="teacher_id" name="teacher_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Choose a teacher...</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="session_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Session Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="session_date" name="session_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               min="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                              placeholder="Provide details about the topics to be covered in this doubt session..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Publication Status <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" checked
                                   class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Draft</strong> - Save as draft, students won't see this session
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published"
                                   class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <strong>Published</strong> - Make this session visible to students immediately
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="closeDoubtSessionModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        Create Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Trigger generic notification (reused from PTM logic)
function triggerDoubtNotification(notification) {
    // Show notification badge on bell icon
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        badge.classList.remove('hidden');
        if (!badge.textContent) {
            badge.textContent = '1';
        }
    }
    
    const bellIcon = document.querySelector('.notification-bell');
    if (bellIcon) {
        bellIcon.classList.add('animate-pulse');
    }
    
    // Store notification in session storage for student dashboard
    sessionStorage.setItem('doubtNotification', JSON.stringify(notification));
}

async function publishDoubtSession(event) {
    event.preventDefault();
    
    const form = document.getElementById('doubtSessionForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Creating...';
    
    try {
        const response = await fetch("{{ route('admin.doubt-sessions.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Trigger visual notification bell pulse for Admin
            if (data.notification) {
                triggerDoubtNotification(data.notification);
            }
            
            // Reload page to show new data
            window.location.reload();
        } else {
            // Error handling
            let errorMsg = data.message || 'An error occurred';
            if (data.errors) {
               errorMsg = Object.values(data.errors).flat().join('\n');
            }
            alert(errorMsg);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
}

document.addEventListener('DOMContentLoaded', function() {
    // Load saved timetable and update displays
    const savedTimetable = localStorage.getItem(`timetable_{{ $className }}`);
    if (savedTimetable) {
        const data = JSON.parse(savedTimetable);
        
        // Load period timings
        if (data.period_timings) {
            for (let i = 1; i <= 5; i++) {
                const periodData = data.period_timings[`period${i}`];
                const periodElement = document.getElementById(`period${i}-time`);
                if (periodData && periodElement) {
                    if (periodData.start && periodData.end) {
                        periodElement.textContent = `${periodData.start} - ${periodData.end}`;
                    } else {
                        periodElement.textContent = 'Not set';
                    }
                }
            }
        }
        
        // Load schedule and update displays
        if (data.schedule) {
            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            days.forEach(day => {
                const dayData = data.schedule[day];
                if (dayData) {
                    for (let period = 1; period <= 5; period++) {
                        const subjectId = dayData[`period${period}`];
                        const select = document.querySelector(`select[data-day="${day}"][data-period="${period}"]`);
                        if (subjectId && select) {
                            select.value = subjectId;
                        }
                    }
                }
            });
        }
    }
});

// Save Timetable function
function saveTimetable() {
    const numberOfPeriods = parseInt(document.getElementById('numberOfPeriods').value);
    const numberOfDays = parseInt(document.getElementById('numberOfDays').value);
    
    const timetableData = {
        class_name: '{{ $className }}',
        number_of_periods: numberOfPeriods,
        number_of_days: numberOfDays,
        period_timings: {},
        schedule: {}
    };
    
    // Collect period timings
    for (let i = 1; i <= numberOfPeriods; i++) {
        const startTime = document.getElementById(`period${i}-start`).value;
        const endTime = document.getElementById(`period${i}-end`).value;
        timetableData.period_timings[`period${i}`] = {
            start: startTime,
            end: endTime
        };
    }
    
    // Collect schedule data
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].slice(0, numberOfDays);
    days.forEach(day => {
        timetableData.schedule[day] = {};
        for (let period = 1; period <= numberOfPeriods; period++) {
            const select = document.querySelector(`select[data-day="${day}"][data-period="${period}"]`);
            timetableData.schedule[day][`period${period}`] = select.value;
        }
    });
    
    // Send data to server
    fetch('/admin/subjects/save-timetable', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
        },
        body: JSON.stringify(timetableData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg z-50 shadow-lg';
            successDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Timetable saved successfully! ${numberOfPeriods}-period timetable for ${numberOfDays} days has been saved.
                </div>
            `;
            document.body.appendChild(successDiv);
            
            // Remove success message after 10 minutes
            setTimeout(() => {
                successDiv.remove();
            }, 600000);
            
            // Also store in localStorage for backup
            localStorage.setItem(`timetable_{{ $className }}`, JSON.stringify(timetableData));
        } else {
            throw new Error(data.message || 'Failed to save timetable');
        }
    })
    .catch(error => {
        console.error('Error saving timetable:', error);
        
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg z-50 shadow-lg';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Failed to save timetable. Please try again.
            </div>
        `;
        document.body.appendChild(errorDiv);
        
        // Remove error message after 3 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    });
}

// Load saved timetable on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load existing timetable from database
    fetch(`/admin/subjects/timetable/{{ $className }}`)
        .then(response => response.json())
        .then(data => {
            if (data.timetable || data.period_timings) {
                // Set configuration values if they exist
                if (data.number_of_periods) {
                    document.getElementById('numberOfPeriods').value = data.number_of_periods;
                }
                if (data.number_of_days) {
                    document.getElementById('numberOfDays').value = data.number_of_days;
                }
                
                // Generate timetable with loaded configuration
                generateTimetable();
                
                // Load period timings after a short delay to ensure DOM is ready
                setTimeout(() => {
                    if (data.period_timings) {
                        Object.keys(data.period_timings).forEach(period => {
                            const timing = data.period_timings[period];
                            const startInput = document.getElementById(`${period}-start`);
                            const endInput = document.getElementById(`${period}-end`);
                            if (startInput && timing.start_time) {
                                startInput.value = timing.start_time;
                            }
                            if (endInput && timing.end_time) {
                                endInput.value = timing.end_time;
                            }
                        });
                    }
                    
                    // Load schedule
                    if (data.timetable) {
                        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                        days.forEach(day => {
                            const dayData = data.timetable[day];
                            if (dayData) {
                                Object.keys(dayData).forEach(period => {
                                    const periodData = dayData[period];
                                    const entry = Array.isArray(periodData) ? periodData[0] : periodData;
                                    const select = document.querySelector(`select[data-day="${day}"][data-period="${period}"]`);
                                    if (select && entry && entry.subject_id) {
                                        select.value = entry.subject_id;
                                    }
                                });
                            }
                        });
                    }
                }, 200);
            } else {
                // Generate default timetable if no saved data
                generateTimetable();
            }
        })
        .catch(error => {
            console.error('Error loading timetable:', error);
            // Generate default timetable on error
            generateTimetable();
        });
});

// Function to publish timetable
function publishTimetable() {
    const className = '{{ $className }}';
    
    if (!confirm(`Are you sure you want to publish the timetable for Class ${className}? This will make it visible to all students.`)) {
        return;
    }
    
    fetch('/admin/subjects/publish-timetable', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            class_name: className
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg z-50 shadow-lg';
            successDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    ${data.message}
                </div>
            `;
            document.body.appendChild(successDiv);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        } else {
            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg z-50 shadow-lg';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Failed to publish timetable. Please try again.
                </div>
            `;
            document.body.appendChild(errorDiv);
            
            // Remove error message after 3 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error publishing timetable:', error);
        
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg z-50 shadow-lg';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Failed to publish timetable. Please try again.
            </div>
        `;
        document.body.appendChild(errorDiv);
        
        // Remove error message after 3 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    });
}

// Function to publish subjects
function publishSubjects() {
    const className = '{{ $className }}';
    
    if (!confirm(`Are you sure you want to publish the subjects for Class ${className}? This will notify all students.`)) {
        return;
    }
    
    fetch('/admin/subjects/publish-subjects', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            class_name: className
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg z-50 shadow-lg';
            successDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    ${data.message}
                </div>
            `;
            document.body.appendChild(successDiv);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        } else {
            alert(data.message || 'Failed to publish subjects');
        }
    })
    .catch(error => {
        console.error('Error publishing subjects:', error);
        alert('An error occurred. Please try again.');
    });
}

// Modal functionality
function openAddSubjectModal(className = null) {
    if (className) {
        document.getElementById('modalClassName').value = className;
    }
    document.getElementById('addSubjectModal').classList.remove('hidden');
}

function closeAddSubjectModal() {
    document.getElementById('addSubjectModal').classList.add('hidden');
    
    // Clear form fields
    document.getElementById('subject_name').value = '';
    document.getElementById('subject_code').value = '';
    document.getElementById('course_name').value = 'REGULAR';
    document.getElementById('program_type').value = 'Regular';
    document.getElementById('teacher_name').value = '';
    document.getElementById('teacher_email').value = '';
    document.getElementById('description').value = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addSubjectModal');
    if (event.target == modal) {
        closeAddSubjectModal();
    }
}

// Function to generate timetable dynamically
function generateTimetable() {
    const numberOfPeriods = parseInt(document.getElementById('numberOfPeriods').value);
    const numberOfDays = parseInt(document.getElementById('numberOfDays').value);
    
    // Generate period timing inputs
    generatePeriodTimings(numberOfPeriods);
    
    // Generate timetable grid
    generateTimetableGrid(numberOfPeriods, numberOfDays);
}

// Function to generate period timing inputs
function generatePeriodTimings(numberOfPeriods) {
    const container = document.getElementById('period-timings-container');
    container.innerHTML = '';
    
    for (let i = 1; i <= numberOfPeriods; i++) {
        const periodDiv = document.createElement('div');
        periodDiv.className = 'bg-gray-50 p-3 rounded-lg border border-gray-200';
        periodDiv.innerHTML = `
            <label class="block text-sm font-semibold text-gray-800 mb-2">Period ${i}</label>
            <div class="space-y-2">
                <input type="time" id="period${i}-start" 
                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Start">
                <input type="time" id="period${i}-end" 
                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="End">
            </div>
        `;
        container.appendChild(periodDiv);
    }
}

// Function to generate timetable grid
function generateTimetableGrid(numberOfPeriods, numberOfDays) {
    const headerRow = document.getElementById('timetable-header').querySelector('tr');
    const tbody = document.getElementById('timetable-body');
    
    // Clear existing content (except first column)
    headerRow.innerHTML = '<th class="border border-gray-200 px-4 py-2 text-left text-sm font-medium text-gray-900">Day / Period</th>';
    tbody.innerHTML = '';
    
    // Generate period headers
    for (let i = 1; i <= numberOfPeriods; i++) {
        const th = document.createElement('th');
        th.className = 'border border-gray-200 px-2 py-2 text-center text-sm font-medium text-gray-900';
        th.textContent = `Period ${i}`;
        headerRow.appendChild(th);
    }
    
    // Define days based on selection
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].slice(0, numberOfDays);
    const dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'].slice(0, numberOfDays);
    
    // Generate day rows
    days.forEach((day, index) => {
        const tr = document.createElement('tr');
        
        // Day name cell
        const dayCell = document.createElement('td');
        dayCell.className = 'border border-gray-200 px-4 py-2 font-medium text-gray-900';
        dayCell.textContent = dayNames[index];
        tr.appendChild(dayCell);
        
        // Period cells for this day
        for (let i = 1; i <= numberOfPeriods; i++) {
            const td = document.createElement('td');
            td.className = 'border border-gray-200 px-2 py-2';
            
            const select = document.createElement('select');
            select.className = 'w-full px-2 py-1 border border-gray-300 rounded text-sm subject-select';
            select.setAttribute('data-day', day);
            select.setAttribute('data-period', i);
            
            // Add default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'No Class';
            select.appendChild(defaultOption);
            
            // Add subject options (these should be loaded from server)
            // For now, we'll add a placeholder
            const subjectOption = document.createElement('option');
            subjectOption.value = '1';
            subjectOption.textContent = 'Loading subjects...';
            select.appendChild(subjectOption);
            
            td.appendChild(select);
            tr.appendChild(td);
        }
        
        tbody.appendChild(tr);
    });
    
    // Load subjects for the class
    loadSubjectsForTimetable();
}

// Function to load subjects for timetable
function loadSubjectsForTimetable() {
    const className = '{{ $className }}';
    
    fetch(`/admin/doubt-sessions/subjects/${className}`)
        .then(response => response.json())
        .then(subjects => {
            // Update all subject selects with loaded subjects
            document.querySelectorAll('.subject-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">No Class</option>';
                
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    if (subject.id == currentValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            });
        })
        .catch(error => console.error('Error loading subjects:', error));
}

// Initialize timetable on page load
document.addEventListener('DOMContentLoaded', function() {
    // Generate initial timetable with default values
    setTimeout(() => {
        generateTimetable();
    }, 100);
});

// Filter subjects by course name or program type
function filterSubjects(filter) {
    const subjectRows = document.querySelectorAll('.subject-row');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    filterButtons.forEach(btn => {
        if (btn.dataset.filter === filter) {
            btn.className = 'filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-blue-600 text-white';
        } else {
            btn.className = 'filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors bg-gray-200 text-gray-700 hover:bg-gray-300';
        }
    });
    
    // Filter subjects
    subjectRows.forEach(row => {
        if (filter === 'all') {
            row.style.display = '';
        } else {
            const courseName = row.dataset.courseName;
            const programType = row.dataset.programType;
            
            if (courseName === filter || programType === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

// Doubt Session Modal functionality
function openDoubtSessionModal(className) {
    // Load subjects for this class
    fetch(`/admin/doubt-sessions/subjects/${className}`)
        .then(response => response.json())
        .then(data => {
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">Choose a subject...</option>';
            data.forEach(subject => {
                subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
            });
        })
        .catch(error => console.error('Error loading subjects:', error));

    // Load teachers
    fetch('/api/teachers')
        .then(response => response.json())
        .then(data => {
            const teacherSelect = document.getElementById('teacher_id');
            teacherSelect.innerHTML = '<option value="">Choose a teacher...</option>';
            data.forEach(teacher => {
                teacherSelect.innerHTML += `<option value="${teacher.id}">${teacher.name}</option>`;
            });
        })
        .catch(error => console.error('Error loading teachers:', error));

    document.getElementById('doubtSessionModal').classList.remove('hidden');
}

function closeDoubtSessionModal() {
    document.getElementById('doubtSessionModal').classList.add('hidden');
    
    // Clear form fields
    document.getElementById('subject_id').value = '';
    document.getElementById('teacher_id').value = '';
    document.getElementById('session_date').value = '';
    document.getElementById('start_time').value = '';
    document.getElementById('end_time').value = '';
    document.getElementById('description').value = '';
    document.querySelector('input[name="status"][value="draft"]').checked = true;
}
</script>
@endsection
