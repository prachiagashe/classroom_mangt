<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordOtpMail;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->forget('login_attempts');
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            } else {
                // Default to enquiry dashboard (for admin and other roles)
                return redirect()->route('enquiry.dashboard');
            }
        }

        // Increment failed login attempts
        $attempts = $request->session()->get('login_attempts', 0) + 1;
        $request->session()->put('login_attempts', $attempts);

        if ($attempts >= 3) {
            $request->session()->flash('suggest_reset', true);
        }

        return back()->with('error', 'Invalid email or password.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear cache to prevent back navigation
        $request->session()->flush();
        return redirect('/')->with('logout_success', 'You have been logged out successfully.');
    }

    // Show forgot password form
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Handle sending OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'No user found with this email address.')->withInput();
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_otps')->insert([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'used' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Mail::to($request->email)->send(new ResetPasswordOtpMail($otp));

        return redirect()->route('password.reset', ['email' => $request->email])->with('success', 'OTP has been sent to your email.');
    }

    // Show reset password form
    public function showResetPassword(Request $request)
    {
        if (!$request->has('email')) {
            return redirect()->route('password.request')->with('error', 'Please submit your email first.');
        }
        return view('auth.reset-password');
    }

    // Handle updating the password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $otpRecord = DB::table('password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Invalid or expired OTP.')->withInput(['email' => $request->email]);
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        DB::table('password_otps')->where('id', $otpRecord->id)->update([
            'used' => true,
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('login')->with('success', 'Your password has been successfully reset. You can now login.');
    }
}
