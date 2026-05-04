<div class="flex justify-between items-center px-4 md:px-6 py-4">

    <!-- Mobile Menu Toggle -->
    <button onclick="toggleSidebar()" 
            class="md:hidden p-2 text-gray-600 hover:text-gray-900 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Left Side (Optional Title) -->
    <div class="flex items-center gap-3 flex-1">
        <!-- Sidebar Toggle Button for Full Page View -->
        <button id="sidebarToggle" onclick="toggleSidebarDesktop()" 
                class="hidden xl:block p-2 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        
        <h2 class="text-lg font-semibold text-gray-700">
            @yield('page-title', 'CRM Dashboard')
        </h2>
    </div>

    <!-- Right Side User Section -->
    @auth
    <div class="relative flex items-center gap-4">
        {{-- Notifications --}}
        <div class="relative">
            <button id="notificationButton" onclick="toggleNotificationDropdown()" 
                    class="notification-bell relative p-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span id="notificationBadge" class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center bg-red-500 text-white text-[10px] font-bold rounded-full px-1 leading-none"></span>
            </button>
            
            <!-- Notification Dropdown -->
            <div id="notificationDropdown" 
                 class="hidden absolute right-0 mt-2 w-80 bg-white border rounded-xl shadow-lg z-50">
                
                <!-- Header -->
                <div class="px-4 py-3 border-b bg-gray-50 rounded-t-xl flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                    <span id="notificationCountLabel" class="text-xs text-gray-500"></span>
                </div>
                
                <!-- Notification Content -->
                <div id="notificationContent" class="max-h-64 overflow-y-auto">
                    <!-- Notifications will be loaded via JavaScript -->
                    <div id="notificationLoader" class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" strokejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <p class="text-sm">Loading notifications...</p>
                    </div>
                    <div id="notificationList" class="hidden">
                        <!-- Notifications will be populated here -->
                    </div>
                </div>
                
                <!-- Footer -->
                <div id="notificationFooter" class="hidden px-4 py-3 border-t bg-gray-50 rounded-b-xl text-center">
                    <button onclick="markNotificationsAsRead()" 
                            class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center justify-center gap-2 mx-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark all as read
                    </button>
                </div>
            </div>
        </div>
        
        <div class="relative">
            <button id="userMenuButton" onclick="toggleUserMenu()"
                    class="flex items-center gap-3 focus:outline-none">

                <!-- Avatar -->
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-9 h-9 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-9 h-9 @if(Auth::user()->role === 'student') bg-gray-200 text-gray-500 @else bg-blue-600 text-white @endif rounded-full flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <!-- User Info -->
                <div class="text-left">
                    <!-- Name -->
                    <span class="text-gray-700 font-medium block">
                        {{ Auth::user()->name }}
                    </span>
                    <!-- Role -->
                    <div class="text-xs text-gray-500">
                        @if(Auth::user()->role === 'admin')
                            Administrator
                        @elseif(Auth::user()->role === 'teacher')
                            Teacher
                        @elseif(Auth::user()->role === 'student')
                            Student
                        @else
                            User
                        @endif
                    </div>
                </div>
                
                <!-- Arrow -->
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown -->
            <div id="userDropdown"
                 class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-xl shadow-lg z-50">

                <div class="px-4 py-3 border-b">
                    <p class="font-semibold text-gray-800">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </p>
                </div>

                <a href="{{ Auth::user()->role === 'student' ? route('student.profile.index') : route('profile.index') }}"
                   onclick="window.location='{{ Auth::user()->role === 'student' ? route('student.profile.index') : route('profile.index') }}'"
                   class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    Profile Settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 text-sm">
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>
    <audio id="notificationSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto" class="hidden"></audio>
    @endauth

</div>

<script>
function toggleUserMenu(event) {
    if (event) event.stopPropagation();
    const userDropdown = document.getElementById('userDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Close notification dropdown if it's open
    notificationDropdown?.classList.add('hidden');
    
    // Toggle user menu
    userDropdown?.classList.toggle('hidden');
}

function toggleNotificationDropdown(event) {
    if (event) event.stopPropagation();
    const userDropdown = document.getElementById('userDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Close user dropdown if it's open
    userDropdown?.classList.add('hidden');
    
    // Toggle notification dropdown
    const isOpening = notificationDropdown?.classList.contains('hidden');
    notificationDropdown?.classList.toggle('hidden');
    
    // Load notifications when dropdown is opened
    if (isOpening) {
        loadNotifications();
    }
}

async function loadNotifications() {
    const loader = document.getElementById('notificationLoader');
    const notificationList = document.getElementById('notificationList');
    const notificationFooter = document.getElementById('notificationFooter');
    
    // Show loader
    loader.classList.remove('hidden');
    notificationList.classList.add('hidden');
    notificationFooter.classList.add('hidden');
    
    try {
        const response = await fetch('/api/notifications', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Hide loader
        loader.classList.add('hidden');
        
        if (data.notifications && data.notifications.length > 0) {
            let notificationHTML = '';
            data.notifications.forEach(notification => {
                let message = notification.message || notification.title;
                let actionUrl = '#';
                
                // Handle different types of notifications - Use redirect_url from data if available
                if (notification.data && notification.data.redirect_url) {
                    actionUrl = notification.data.redirect_url;
                } else {
                    // Fallback legacy logic
                    if (notification.type === 'leave_request') {
                        let leaveId = notification.data && notification.data.leave_request_id ? notification.data.leave_request_id : '';
                        actionUrl = `/admin/leave?tab=leaves&leave_id=${leaveId}`;
                    } else if (notification.type === 'leave_status') {
                        actionUrl = '/teacher/leaves';
                    } else if (notification.type === 'ptm') {
                        actionUrl = '/student/ptm/meetings';
                    } else if (notification.type === 'doubt') {
                        actionUrl = '/student/doubt-sessions';
                    } else if (notification.type === 'timetable') {
                        actionUrl = '/student/timetable';
                    } else if (notification.type === 'subject') {
                        actionUrl = '/student/courses';
                    }
                }
                
                // Use short_message from backend if available
                let displayMessage = notification.short_message || message;
                let isUnread = !notification.is_read;

                notificationHTML += `
                    <div class="px-4 py-3 border-b hover:bg-gray-50 flex items-center justify-between gap-3 notification-item cursor-pointer ${isUnread ? 'bg-blue-50/50' : 'opacity-70'}" 
                         onclick="markIndividualNotificationAsRead('${notification.id}')"
                         data-notification-id="${notification.id}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-[13px] font-bold text-blue-600 uppercase tracking-wider">${notification.title}</p>
                                ${isUnread ? '<span class="w-2 h-2 bg-blue-500 rounded-full"></span>' : ''}
                            </div>
                            <p class="text-sm text-gray-700 font-medium truncate">${displayMessage}</p>
                            <p class="secondary-text mt-1">${new Date(notification.created_at).toLocaleString()}</p>
                        </div>
                        <a href="${actionUrl}" 
                           onclick="event.stopPropagation(); markIndividualNotificationAsRead('${notification.id}')"
                           class="flex-shrink-0 px-3 py-1.5 bg-blue-600 text-white text-[11px] font-bold rounded-lg hover:bg-blue-700 transition-colors uppercase tracking-wider">
                            View
                        </a>
                    </div>
                `;
            });
            
            notificationList.innerHTML = notificationHTML;
            notificationList.classList.remove('hidden');
            notificationFooter.classList.remove('hidden');
        } else {
            // Show no notifications message
            notificationList.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" strokelinejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm">No new notifications</p>
                </div>
            `;
            notificationList.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
        loader.classList.add('hidden');
        notificationList.innerHTML = `
            <div class="p-8 text-center text-red-500">
                <p class="text-sm">Error loading notifications</p>
            </div>
        `;
        notificationList.classList.remove('hidden');
    }
}
let lastUnreadCount = 0;

async function checkNotificationCount() {
    try {
        const response = await fetch('/api/notifications/check-new', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            return; // Silently fail if session expires instead of throwing JSON parser error
        }
        
        const data = await response.json();
        const currentCount = data.unread_count || 0;
        
        const badge = document.getElementById('notificationBadge');
        
        if (currentCount > 0) {
            badge.textContent = currentCount > 99 ? '99+' : currentCount;
            badge.classList.remove('hidden');
            
            // Play sound only when unread count increases (new notification arrived)
            if (currentCount > lastUnreadCount) {
                const sound = document.getElementById('notificationSound');
                if (sound) {
                    sound.play().catch(error => {
                        console.log('Audio playback delayed until user interaction:', error);
                    });
                }
            }
        } else {
            badge.classList.add('hidden');
        }
        lastUnreadCount = currentCount;
    } catch (error) {
        console.error('Error checking notification count:', error);
    }
}

function markNotificationsAsRead() {
    fetch('/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('notificationBadge');
            badge.classList.add('hidden');
            lastUnreadCount = 0;
            
            // Remove all items from the dropdown list since they are now read
            const notificationList = document.getElementById('notificationList');
            notificationList.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm">No new notifications</p>
                </div>
            `;
            document.getElementById('notificationFooter').classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error marking notifications as read:', error);
    });
}

function markIndividualNotificationAsRead(notificationId) {
    // Send to server to mark as read
    fetch('/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            notification_id: notificationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI by removing the notification completely
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.remove();
                
                // If it was the last notification, show the empty state
                const notificationList = document.getElementById('notificationList');
                if (notificationList.querySelectorAll('.notification-item').length === 0) {
                    notificationList.innerHTML = `
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <p class="text-sm">No new notifications</p>
                        </div>
                    `;
                    document.getElementById('notificationFooter').classList.add('hidden');
                }
            }
            checkNotificationCount();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function showPTMNotification() {
    checkNotificationCount();
}

// Initialize notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    showPTMNotification();
    
    // Periodically check for notifications every 5 seconds
    setInterval(checkNotificationCount, 5000);
});

document.addEventListener('click', function(e) {
    const userDropdown = document.getElementById('userDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userMenuButton = document.getElementById('userMenuButton');
    const notificationButton = document.getElementById('notificationButton');

    // Close user dropdown if click is outside the button and the dropdown itself
    if (userDropdown && !userDropdown.classList.contains('hidden')) {
        if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    }

    // Close notification dropdown if click is outside the button and the dropdown itself
    if (notificationDropdown && !notificationDropdown.classList.contains('hidden')) {
        if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
    }
});
</script>
