<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\User;
use App\Traits\SendsAuthEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class TeacherRegistrationController extends Controller
{
    use SendsAuthEmails;
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.registration');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check if email exists in employees table
        $employee = Employee::where('email', $request->email)->first();
        if (!$employee) {
            return back()
                ->withErrors(['email' => 'This email is not registered by admin.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Check if email already exists in users table
        if (User::where('email', $request->email)->exists()) {
            return back()
                ->withErrors(['email' => 'Account already exists. Please login.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Construct name from employee record
        $nameParts = array_filter([$employee->first_name, $employee->middle_name, $employee->last_name]);
        $fullName = implode(' ', $nameParts);

        // Create new user
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'phone' => $employee->phone ?? '',
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'is_first_login' => 0,
        ]);

        // Send confirmation email
        \Illuminate\Support\Facades\Mail::to($user->email)->send(
            new \App\Mail\TeacherRegistrationConfirmation(
                $fullName,
                $user->email,
                $employee->department ?? 'N/A',
                $employee->assigned_classes ?? 'N/A',
                $employee->assigned_subjects ?? 'N/A'
            )
        );

        // Redirect to teacher login
        return redirect()->route('teacher.login')
            ->with('success_title', 'Registration Successful')
            ->with('success', 'Your teacher account has been created successfully. Please login to continue.');
    }
}
