@extends('layouts.app')

@section('title', __('profile.page_title'))

@section('content')

<div class="min-h-screen {{ $preferences['dark_mode'] ? 'bg-gray-900' : 'bg-gray-50' }} py-8 transition-colors duration-300" id="app-root">
    <div class="w-full mx-auto px-6 lg:px-12">

        @if(session('success'))
        <div class="mb-6 {{ $preferences['dark_mode'] ? 'bg-green-900 border-green-700 text-green-200' : 'bg-green-50 border-green-200 text-green-800' }} border px-4 py-3 rounded-lg transition-colors duration-300">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:items-start">
            <!-- Left Column: Personal Info (takes more space) -->
            <div class="lg:col-span-12 xl:col-span-8 space-y-6">
            <!-- Personal Info Section -->
            <div class="{{ $preferences['dark_mode'] ? 'bg-gray-800 text-white' : 'bg-white text-gray-900' }} rounded-lg shadow-md p-8 transition-colors duration-300">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                        <h2 class="text-2xl font-semibold">Personal Information</h2>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Avatar Section -->
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div class="w-32 h-32 rounded-full bg-blue-500 overflow-hidden border-4 {{ $preferences['dark_mode']? 'border-gray-600' : 'border-gray-200' }} flex items-center justify-center text-white text-5xl font-bold shadow-md">
                                    @if($avatar)
                                        <img src="{{ asset('storage/' . $avatar) }}" alt="avatar" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <label for="avatar-input" class="absolute bottom-2 right-2 bg-gray-400 rounded-full p-2 cursor-pointer hover:bg-gray-500 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                </label>
                            </div>
                            <p class="mt-4 text-lg font-semibold">{{ substr($user->name, 0, 1) }}</p>
                            <p class="text-sm {{ $preferences['dark_mode'] ? 'text-gray-400' : 'text-gray-600' }} mb-2">{{ $user->name }}</p>
                            
                            <div class="flex flex-col gap-2">
                                <label for="avatar-input" class="px-4 py-2 {{ $preferences['dark_mode'] ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }} border rounded-lg font-medium cursor-pointer transition-colors duration-300 text-center">Upload Photo</label>
                                @if($avatar)
                                    <button onclick="event.preventDefault(); if(confirm('Are you sure you want to remove your profile photo?')) document.getElementById('remove-avatar-form').submit();" 
                                            class="text-red-500 text-xs font-medium hover:underline">
                                        Remove Photo
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Form Fields Section -->
                        <div class="flex-1 space-y-6">
                            <!-- Row 1: Full Name and Email -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300">
                                </div>
                            </div>

                            <!-- Row 2: Phone and Role -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">Phone Number</label>
                                    <div class="flex items-center gap-2">
                                        <span class="{{ $preferences['dark_mode'] ? 'text-gray-400' : 'text-gray-500' }}">🇮🇳 +91</span>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="flex-1 {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">User Role</label>
                                    <input type="text" value="@if($user->role === 'admin') Administrator @elseif($user->role === 'teacher') Teacher @elseif($user->role === 'student') Student @else User @endif" readonly class="w-full {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="reset" class="px-6 py-2 {{ $preferences['dark_mode'] ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }} border rounded-lg font-medium transition-colors duration-300">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-300">Save Changes</button>
                    </div>
                </form>

                <!-- Hidden Avatar Forms -->
                <form id="avatar-form" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="avatar" accept="image/*" id="avatar-input" onchange="this.form.submit()">
                </form>
                
                @if($avatar)
                    <form id="remove-avatar-form" action="{{ route('profile.avatar.remove') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>

        <!-- Right Column: Security & Logout (takes less space) -->
        <div class="lg:col-span-12 xl:col-span-4 space-y-6">
                <!-- Security Section -->
            <div class="{{ $preferences['dark_mode'] ? 'bg-gray-800 text-white' : 'bg-white text-gray-900' }} rounded-lg shadow-md p-8 transition-colors duration-300">
                <div class="flex items-center gap-3 mb-8">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    <h2 class="text-xl font-semibold">Security</h2>
                </div>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    
                    <!-- Change Password Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-3">Change Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">Current Password</label>
                                <input type="password" name="current_password" class="w-full {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300" placeholder="Current Password">
                            </div>
                            <div>
                                <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">New Password</label>
                                <input type="password" name="password" class="w-full {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300" placeholder="New Password">
                            </div>
                            <div>
                                <label class="block text-sm font-medium {{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="w-full {{ $preferences['dark_mode'] ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300' }} border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors duration-300" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>

                    <!-- Last Login Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start pt-6 {{ $preferences['dark_mode'] ? 'border-gray-600' : 'border-gray-200' }} border-t gap-6">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold mb-3">Last Login Information</h3>
                            <p class="{{ $preferences['dark_mode'] ? 'text-gray-300' : 'text-gray-700' }} text-sm font-medium mb-3" id="current-time"></p>
                            <div class="flex items-start gap-2 {{ $preferences['dark_mode'] ? 'text-gray-400' : 'text-gray-600' }} text-sm mb-2">
                                <span class="text-blue-500 mt-0.5">🔵</span>
                                <div>
                                    <p>{{ $lastLogin['agent'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 {{ $preferences['dark_mode'] ? 'text-gray-400' : 'text-gray-600' }} text-sm mb-2">
                                <span>🌐</span>
                                <p><span class="font-medium">IP Address:</span> {{ $lastLogin['ip'] }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 w-full md:w-auto">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-300">Update Password</button>
                            <a href="#" class="px-6 py-2 text-center text-blue-600 font-medium hover:underline">View All Sessions</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Logout Section -->
            <div class="{{ $preferences['dark_mode'] ? 'bg-gray-800 text-white' : 'bg-white' }} rounded-lg shadow-md p-6 transition-colors duration-300 text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 text-red-500 font-semibold py-2 hover:text-red-600 transition mx-auto">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Display current server time
    const serverTimestamp = {{ $lastLogin['timestamp'] }} * 1000; // Convert to milliseconds
    const timeElement = document.getElementById('current-time');
    
    function updateTime() {
        const now = new Date();
        const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
        const formattedTime = now.toLocaleDateString('en-US', options);
        timeElement.textContent = formattedTime;
    }
    
    // Update immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
</script>

@endsection
