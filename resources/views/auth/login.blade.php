<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StudyFlow Classes CRM - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    {{-- Font Awesome for eye icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

<!-- Header -->
<div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between z-10 relative">

    <!-- Logo -->
    <div class="flex items-center gap-3">
        <div class="flex items-center justify-center w-12 h-12">
            <img src="{{ asset('images/icon.png') }}" 
                 alt="Logo" 
                 class="w-12 h-12 object-contain">
        </div>
        <div>
            <h2 class="font-bold text-lg">StudyFlow Classes</h2>
            <p class="text-sm text-gray-500">CRM & Management System</p>
        </div>
    </div>

    <!-- Enquiry Button -->
    <a href="{{ route('enquiry.form') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors">
        Enquiry Form
    </a>
</div>

<!-- Main Content -->
<div class="flex-1 flex">
    <!-- Left Section - Education Illustration -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100 items-center justify-center p-12">
        <div class="text-center space-y-6">
            <!-- Education Illustration -->
            <div class="relative">
                <img src="{{ asset('images/loginpageimage.jpeg') }}" 
                     alt="Students Learning" 
                     class="rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                <!-- Overlay badges -->
                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-blue-600"></i>
                        <span class="text-sm font-semibold">Excellence in Education</span>
                    </div>
                </div>
                <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-award text-green-600"></i>
                        <span class="text-sm font-semibold">Since 1990</span>
                    </div>
                </div>
            </div>
            
            <!-- Educational Content -->
            <div class="space-y-4">
                <h1 class="text-3xl font-bold text-gray-900">
                    Empowering Students to Achieve Excellence
                </h1>
                <p class="text-gray-600 max-w-md mx-auto">
                    Join thousands of successful students who have transformed their careers with StudyFlow Classes' comprehensive educational programs.
                </p>
                <!-- Stats -->
                <div class="flex justify-center gap-8 pt-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">10,000+</div>
                        <div class="text-sm text-gray-600">Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">95%</div>
                        <div class="text-sm text-gray-600">Success Rate</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">30+</div>
                        <div class="text-sm text-gray-600">Courses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100" style="box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 12px;">
                
                <!-- Title -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-graduation-cap text-2xl text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        Login to Dashboard
                    </h2>
                    <p class="text-sm text-gray-600">
                        Access your StudyFlow Classes CRM account
                    </p>
                </div>

                {{-- Logout Success Message --}}
                @if(session('logout_success'))
                    <div class="bg-green-100 text-green-600 p-3 rounded-lg mb-4 text-sm font-semibold border border-green-200">
                        {{ session('logout_success') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @if(session('error') || $errors->any())
                    <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm font-semibold border border-red-200">
                        {{ session('error') ?? $errors->first() }}
                    </div>
                @endif

                {{-- Password Reset Suggestion --}}
                @if(session('suggest_reset'))
                    <div class="bg-yellow-50 text-yellow-700 p-3 rounded-lg mb-4 text-sm font-medium border border-yellow-200 flex justify-between items-center">
                        <span>Having trouble logging in?</span>
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-700 font-semibold underline">Reset Password</a>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email"
                                   name="email"
                                   required
                                   placeholder="Enter your email"
                                   class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="Enter your password"
                                   class="w-full border border-gray-300 rounded-lg pl-10 pr-12 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                            <button type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Forgot Password Link -->
                        <div class="text-right mt-3">
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mb-6">
                        <button type="submit"
                                class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login
                        </button>
                        
                        <a href="{{ route('register') }}" 
                           class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register
                        </a>
                    </div>

                    <!-- Additional Info -->
                    <div class="text-center text-sm text-gray-500">
                        <p>Need help? Contact our support team</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Simple Footer -->
<footer class="bg-white border-t border-gray-200 py-4">
    <div class="text-center">
        <p class="text-sm text-gray-600">
            © 2026 StudyFlow Classes. Excellence in Education.
        </p>
    </div>
</footer>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

    <!-- SweetAlert2 Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('enquiry_success') || session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ session('success_title') ?? 'Successful' }}",
                text: "{!! session('enquiry_success') ?? session('success') !!}",
                confirmButtonColor: '#3b82f6'
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{!! session('error') !!}",
                confirmButtonColor: '#3b82f6'
            });
        @endif
    </script>

</body>
</html>