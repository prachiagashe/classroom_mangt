<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Registration</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Create your account to access the CRM
                </p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    <!-- Role Selection (Compact Radio Options) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Register As: <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="role" value="student" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" required {{ old('role') == 'student' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">🎓 Student</span>
                            </label>

                            <label class="flex items-center cursor-pointer group">
                                <input type="radio" name="role" value="teacher" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" required {{ old('role') == 'teacher' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">💼 Teacher</span>
                            </label>
                        </div>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required
                                value="{{ old('email') }}"
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter your email address"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" id="email-note">
                            Note: Email must be registered in the respective database
                        </p>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Create a strong password"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Confirm your password"
                            >
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Register Account
                        </button>
                    </div>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            Back to Login
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>&copy; 2026 CRM System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for enhanced UX -->
    <script>
        // Auto-focus first field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Password strength indicator (optional enhancement)
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthIndicator = document.getElementById('password-strength');
            
            if (password.length === 0) {
                if (strengthIndicator) strengthIndicator.remove();
                return;
            }

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            const colors = ['red', 'orange', 'yellow', 'green'];
            const texts = ['Weak', 'Fair', 'Good', 'Strong'];
            
            if (!strengthIndicator) {
                const indicator = document.createElement('div');
                indicator.id = 'password-strength';
                indicator.className = 'mt-1 text-xs';
                e.target.parentNode.appendChild(indicator);
            }

            const indicator = document.getElementById('password-strength');
            indicator.className = `mt-1 text-xs text-${colors[strength]}-600`;
            indicator.textContent = `Password strength: ${texts[strength]}`;
        });
    </script>

    <!-- SweetAlert2 Initialization -->
    <script>
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3b82f6'
            });
        @endif
        
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>
</body>
</html>