<aside class="bg-gray-900 text-white h-full flex flex-col" style="width:260px;">

    <!-- Logo Section -->
    <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-700">
       <div class="flex items-center justify-center w-12 h-12">
    <img src="{{ asset('images/icon.png') }}" 
         alt="Logo" 
         class="w-12 h-12 object-contain">
</div>

        <div>
            <h6 class="mb-0 font-bold">StudyFlow Classes</h6>
            <small class="text-gray-400">Teacher Portal</small>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow px-3 py-4">

    <ul class="space-y-1">

        <li>
            <a href="{{ route('teacher.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('teacher.dashboard')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('teacher.profile') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('teacher.profile')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                My Profile
            </a>
        </li>

        <li>
            <a href="{{ route('teacher.salary.history') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('teacher.salary.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Salary History
            </a>
        </li>

        <li>
            <a href="{{ route('teacher.assignments') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('teacher.assignments*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                My Assignments
            </a>
        </li>

        <li>
            <a href="{{ route('teacher.leaves.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->routeIs('teacher.leaves.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Leave Records
            </a>
        </li>

    </ul>

</nav>

    <!-- User Section -->
    <div class="border-t border-gray-700 p-3">

        <div class="flex items-center gap-3 mb-3">
            <div class="bg-blue-600 rounded-full text-white flex items-center justify-center flex-shrink-0 font-bold"
                 style="width:40px;height:40px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-white truncate" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
                <small class="text-gray-400 block truncate">Teacher</small>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-3 py-2 border border-gray-600 text-white hover:bg-gray-800 rounded">
                Logout
            </button>
        </form>

    </div>

</aside>
