<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - StudyFlow Classes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex flex-col">

    <div class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12">
                <img src="{{ asset('images/icon.png') }}" alt="Logo" class="w-12 h-12 object-contain">
            </div>
            <div>
                <h2 class="font-bold text-lg">StudyFlow Classes</h2>
                <p class="text-sm text-gray-500">CRM & Management System</p>
            </div>
        </div>
        <a href="{{ route('enquiry.form') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
            Enquiry Form
        </a>
    </div>

    <div class="flex flex-1 items-center justify-center px-6">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                Reset Password
            </h2>

            @if(session('error'))
                <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm font-semibold border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ request()->get('email', old('email')) }}" required readonly
                           class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 mb-1">Enter 6-digit OTP</label>
                    <input type="text" name="otp" required maxlength="6"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-center tracking-widest text-lg font-bold">
                    @error('otp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-600 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-200 font-semibold mb-4">
                    Reset Password
                </button>
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm font-medium">Cancel and return to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
