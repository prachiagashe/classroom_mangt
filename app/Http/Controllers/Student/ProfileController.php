<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the student's profile page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student's admission record
        $admission = Admission::where('email', $user->email)->first();
        
        // Fetch phone from admissions if user doesn't have it
        if (!$user->phone && $admission) {
            $user->phone = $admission->contact;
        }
        
        return view('students.profile', compact('user', 'admission'));
    }
    
    /**
     * Update the student's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'sometimes|required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Update user information if provided
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $oldEmail = $user->email;
            $newEmail = $request->email;
            
            if ($oldEmail !== $newEmail) {
                $user->email = $newEmail;
                // Sync email with admission record to maintain consistency
                \App\Models\Admission::where('email', $oldEmail)->update(['email' => $newEmail]);
            }
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('date_of_birth')) {
            // Sync DOB with admission record
            \App\Models\Admission::where('email', $user->email)->update(['date_of_birth' => $request->date_of_birth]);
            // Also sync with Enquiry record
            $admission = \App\Models\Admission::where('email', $user->email)->first();
            if ($admission && $admission->enquiry_id) {
                \App\Models\Enquiry::where('id', $admission->enquiry_id)->update(['dob' => $request->date_of_birth]);
            }
        }
        
        // General sync for name/email
        if ($request->has('name') || $request->has('email') || $request->has('phone')) {
             \App\Models\Admission::where('email', $user->getOriginal('email') ?? $user->email)->update([
                'student_name' => $user->name,
                'email' => $user->email,
                'contact' => $user->phone
            ]);
            
            $admission = \App\Models\Admission::where('email', $user->email)->first();
            if ($admission && $admission->enquiry_id) {
                \App\Models\Enquiry::where('id', $admission->enquiry_id)->update([
                    'first_name' => explode(' ', $user->name)[0] ?? '',
                    'middle_name' => explode(' ', $user->name)[1] ?? '',
                    'surname' => explode(' ', $user->name)[2] ?? '',
                    'email' => $user->email,
                    'parent_mobile' => $user->phone
                ]);
            }
        }
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Upload and optimize new photo
            $file = $request->file('profile_photo');
            $filePath = \App\Services\ImageOptimizer::optimize($file, 'profile_images');
            $user->profile_photo = $filePath;
        }
        
        $user->save();
        
        return redirect()->route('student.profile.index')
            ->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Remove the student's profile photo.
     */
    public function removePhoto()
    {
        $user = Auth::user();
        
        // Delete photo from storage
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }
        
        return redirect()->route('student.profile.index')
            ->with('success', 'Profile photo removed successfully!');
    }
    
    /**
     * Get profile photo URL or generate fallback avatar.
     */
    public static function getProfilePhotoUrl($user)
    {
        if ($user->profile_photo) {
            return asset('storage/' . $user->profile_photo);
        }
        
        // Generate fallback avatar with first letter
        $firstLetter = strtoupper(substr($user->name, 0, 1));
        $avatarUrl = "https://ui-avatars.com/api/?name={$firstLetter}&color=7F9CF5&background=EBF4FF&size=128&bold=true";
        
        return $avatarUrl;
    }
    
    /**
     * Get profile photo URL for dashboard header (smaller size).
     */
    public static function getHeaderAvatarUrl($user)
    {
        if ($user->profile_photo) {
            return asset('storage/' . $user->profile_photo);
        }
        
        // Generate smaller fallback avatar for header
        $firstLetter = strtoupper(substr($user->name, 0, 1));
        $avatarUrl = "https://ui-avatars.com/api/?name={$firstLetter}&color=7F9CF5&background=EBF4FF&size=32&bold=true";
        
        return $avatarUrl;
    }
}
