<aside class="bg-gray-900 text-white h-full flex flex-col" style="width:260px;">

    <!-- Logo Section -->
    <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-700">
       <div class="flex items-center justify-center w-12 h-12">
    <img src="{{ asset('images/icon.png') }}" 
         alt="Logo" 
         class="w-12 h-12 object-contain">
</div>

        <div>
            <h6 class="mb-0 font-bold">Bansal Classes</h6>
            <small class="text-gray-400">CRM System</small>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-grow px-3 py-4">

    <ul class="space-y-1">

        <li>
            <a href="{{ route('enquiry.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('enquiry.dashboard')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('calling.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('calling.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Calling
            </a>
        </li>

        <li>
            <a href="{{ route('enquiry.enquiries.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('enquiry.enquiries.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="3" width="6" height="4"/>
                    <path d="M9 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2"/>
                </svg>
                Enquiries
            </a>
        </li>

        <li>
            <a href="{{ route('enquiry.followups.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('enquiry.followups.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92V21a1 1 0 0 1-1.11 1 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 3 4.11 1 1 0 0 1 4 3h4.09a1 1 0 0 1 1 .75 12.05 12.05 0 0 0 .57 2.57 1 1 0 0 1-.23 1L8.09 8.91a16 16 0 0 0 6 6l1.59-1.34a1 1 0 0 1 1-.23 12.05 12.05 0 0 0 2.57.57 1 1 0 0 1 .75 1z"/>
                </svg>
                Follow-ups
            </a>
        </li>

        <li>
            <a href="{{ route('enquiry.admissions.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('enquiry.admissions.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                Confirm Admissions
            </a>
        </li>

       
        <li>
            <a href="{{ route('enquiry.fees') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('enquiry.fees*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Fees Management
            </a>
        </li>


        <li>
            <a href="{{ route('admin.attendance.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('admin.attendance.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Attendance Management
            </a>
        </li>

        <li>
            <a href="{{ route('employee.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('employee.*') 
                    ? 'bg-blue-600 text-white' 
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Employee Management
            </a>
        </li>

        <li>
            <a href="{{ route('salary.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('salary.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Salary Management
            </a>
        </li>

     

        <li>
            <a href="{{ route('admin.leave.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('admin.leave.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Leave Management
            </a>
        </li>

           <li>
            <a href="{{ route('admin.subjects.classes') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('admin.subjects.*')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Subject Management
            </a>
        </li>
        
        
        <li>
            <a href="{{ route('enquiry.reports') }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition {{ request()->routeIs('enquiry.reports*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Reports
            </a>
        </li>

        <!-- <li>
            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded transition text-gray-300 hover:bg-gray-800 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
            </a>
        </li> -->

    </ul>

</nav>


    <!-- User Section -->
    <div class="border-t border-gray-700 p-3">

        <div class="flex items-center gap-3 mb-3">
            <div class="bg-blue-600 rounded-full text-white flex items-center justify-center flex-shrink-0 font-bold"
                 style="width:40px;height:40px;">
                {{ strtoupper(substr(Auth::user()->name ?? 'AU', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-white truncate" title="{{ Auth::user()->name ?? 'Admin User' }}">{{ Auth::user()->name ?? 'Admin User' }}</div>
                <small class="text-gray-400 block truncate">Administrator</small>
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
