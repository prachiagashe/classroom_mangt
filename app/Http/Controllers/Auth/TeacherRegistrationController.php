<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\User;
use App\Traits\SendsAuthEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:teacher,student'],
        ]);

        // Check if email exists in employees table (only for teachers)
        if ($request->role === 'teacher') {
            $employee = Employee::where('email', $request->email)->first();
            if (!$employee) {
                return back()
                    ->withErrors(['email' => 'This email is not registered in the employee database. Please contact admin.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }
        }

        // Check if email already exists in users table
        if (User::where('email', $request->email)->exists()) {
            return back()
                ->withErrors(['email' => 'This email is already registered. Please login.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Use selected role
        ]);

        // Send registration confirmation email
        $this->sendRegistrationConfirmationEmail($user->name, $user->email, $user->role);

        // Redirect to login page with success message
        return redirect()->route('login')
            ->with('success', 'Registration successful. You can now log in to your account.');
    }
}
