<aside class="h-full flex flex-col">

    <!-- Mobile Close Button -->
    <button onclick="toggleSidebar()" 
            class="md:hidden absolute top-4 right-4 p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <!-- Logo Section -->
    <div class="flex items-center gap-3 px-4 py-6 border-b border-purple-400">
        <div class="flex items-center justify-center w-12 h-12 bg-white rounded-lg shadow-lg">
            <img src="{{ asset('images/icon.png') }}" 
                 alt="Logo" 
                 class="w-10 h-10 object-contain">
        </div>

        <div>
            <h6 class="mb-0 font-bold text-white">Bansal Classes</h6>
            <small class="text-purple-200">Student Portal</small>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow px-3 py-6">

        <ul class="space-y-2">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('student.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.dashboard')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- My Courses -->
            <li>
                <a href="{{ route('student.courses') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.courses')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="font-medium">My Courses</span>
                </a>
            </li>

            <!-- Assignments -->
            <li>
                <a href="{{ route('student.assignments') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.assignments')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium">Assignments</span>
                </a>
            </li>

            <!-- Schedule -->
            <li>
                <a href="{{ route('student.schedule') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.schedule')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">Schedule</span>
                </a>
            </li>

            <!-- Timetable -->
            <li>
                <a href="{{ route('student.timetable') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.timetable')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span class="font-medium">Timetable</span>
                </a>
            </li>

            <!-- Attendance -->
            <!-- <li>
                <a href="{{ route('student.attendance.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.attendance.index')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="font-medium">Attendance</span>
                </a>
            </li> -->

            <!-- Grades -->
            <li>
                <a href="{{ route('student.grades') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.grades')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium">Grades</span>
                </a>
            </li>

            <!-- Fees -->
            <li>
                <a href="{{ route('student.fees') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.fees')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Fees</span>
                    <span class="fee-notification-badge hidden ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">1</span>
                </a>
            </li>

            <!-- PTM Meetings -->
            <li>
                <a href="/student/ptm/meetings"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->is('student/ptm*')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium">PTM Meetings</span>
                </a>
            </li>

            <!-- Doubt Sessions -->
            <li>
                <a href="{{ route('student.doubt-sessions') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.doubt-sessions*')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Doubt Sessions</span>
                </a>
            </li>

            <!-- Profile -->
            <li>
                <a href="{{ route('student.profile.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('student.profile.index')
                        ? 'bg-white text-purple-700 shadow-md transform scale-105'
                        : 'text-purple-100 hover:bg-purple-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-medium">Profile</span>
                </a>
            </li>

        </ul>

    </nav>

    <!-- User Info Footer -->
    <div class="border-t border-purple-400 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-purple-600 font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <div class="font-semibold text-white text-sm">{{ Auth::user()->name }}</div>
                <div class="text-purple-200 text-xs">Student ID: #{{ str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

</aside>
