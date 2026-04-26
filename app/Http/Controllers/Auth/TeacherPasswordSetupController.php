<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class TeacherPasswordSetupController extends Controller
{
    /**
     * Display the password setup form.
     */
    public function showSetupForm(Request $request, $token = null)
    {
        return view('auth.teacher-password-setup', ['token' => $token]);
    }

    /**
     * Handle password setup request.
     */
    public function setup(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->role !== 'teacher') {
            return back()->withErrors(['email' => 'Invalid teacher account.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Password set successfully. Please login with your new password.');
    }
}
