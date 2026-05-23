<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Admission;
use App\Models\User;
use App\Traits\SendsAuthEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegistrationController extends Controller
{
    use SendsAuthEmails;

    /**
     * Show the unified registration form.
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
            'role' => ['required', 'in:student,teacher'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = $request->email;
        $role = $request->role;

        // Check if email already exists in users table
        if (User::where('email', $email)->exists()) {
            return back()
                ->with('error', 'Account already registered. Please login.')
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $fullName = '';
        $phone = '';
        $employeeRecord = null;

        if ($role === 'teacher') {
            $employee = Employee::where('email', $email)->first();
            if (!$employee) {
                return back()
                    ->with('error', 'Your email is not authorized for teacher registration.')
                    ->withInput($request->except('password', 'password_confirmation'));
            }
            
            $nameParts = array_filter([$employee->first_name, $employee->middle_name, $employee->last_name]);
            $fullName = implode(' ', $nameParts);
            $phone = $employee->phone ?? '';
            $employeeRecord = $employee;

        } elseif ($role === 'student') {
            $admission = Admission::where('email', $email)->first();
            if (!$admission) {
                return back()
                    ->with('error', 'Your email is not authorized for student registration.')
                    ->withInput($request->except('password', 'password_confirmation'));
            }
            
            $fullName = $admission->student_name;
            $phone = $admission->contact ?? '';
        }

        // Create new user
        $user = User::create([
            'name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => $role,
            'is_first_login' => 0,
        ]);

        // Send confirmation email for teachers only
        if ($role === 'teacher' && $employeeRecord) {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(
                new \App\Mail\TeacherRegistrationConfirmation(
                    $fullName,
                    $user->email,
                    $employeeRecord->department ?? 'N/A',
                    $employeeRecord->assigned_classes ?? 'N/A',
                    $employeeRecord->assigned_subjects ?? 'N/A'
                )
            );
        }

        // Redirect to login for both roles, do not auto-login
        return redirect()->route('login')
            ->with('success_title', 'Registration Successful')
            ->with('success', 'Registration successful. Please login.');
    }
}
