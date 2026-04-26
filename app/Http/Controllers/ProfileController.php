<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get current time and user agent info
        $now = now();
        $lastLogin = [
            'timestamp' => $now->timestamp,
            'agent' => request()->header('User-Agent') ? Str::limit(request()->header('User-Agent'), 60) : 'Unknown',
            'ip' => request()->ip()
        ];

        $preferences = session('profile_preferences', [
            'notifications' => true,
            'dark_mode' => false,
            'language' => 'english',
        ]);

        $avatar = $user->profile_photo ?? null;

        return view('profile.index', compact('user', 'lastLogin', 'preferences', 'avatar'));
    }

    public function updatePersonal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        $user->password = $request->password;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password updated successfully');
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'notifications' => 'nullable|in:on',
        ]);

        $prefs = [
            'notifications' => isset($data['notifications']) && $data['notifications'] === 'on',
        ];

        session(['profile_preferences' => $prefs]);

        return redirect()->route('profile.index')->with('success', 'Preferences updated');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        
        $file = $request->file('avatar');
        $path = \App\Services\ImageOptimizer::optimize($file, 'profile_images');
        
        // Update user profile photo
        $user->profile_photo = $path;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile photo uploaded successfully');
    }

    public function removeAvatar()
    {
        $user = Auth::user();
        
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }

        return redirect()->route('profile.index')->with('success', 'Profile photo removed successfully');
    }

    public function uploadStudentPhoto(Request $request)
    {
        $request->validate([
            'student_email' => 'required|email|exists:users,email',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $studentUser = \App\Models\User::where('email', $request->student_email)->first();
        
        if (!$studentUser) {
            return back()->with('error', 'Student not found.');
        }
        
        // Delete old photo if exists
        if ($studentUser->profile_photo) {
            Storage::disk('public')->delete($studentUser->profile_photo);
        }
        
        $file = $request->file('profile_photo');
        $path = \App\Services\ImageOptimizer::optimize($file, 'profile_images');
        
        // Update student profile photo
        $studentUser->profile_photo = $path;
        $studentUser->save();

        return back()->with('success', 'Student profile photo uploaded successfully');
    }
}
