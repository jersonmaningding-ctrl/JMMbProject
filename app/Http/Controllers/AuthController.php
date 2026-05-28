<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration - creates a student user account
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:user_accounts,email',
            'username' => 'required|string|unique:user_accounts,username|min:3',
            'password' => 'required|min:6|confirmed',
        ]);

        UserAccount::create([
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
            'role' => 'student', // Default role for new registrations
        ]);

        return redirect('/login')->with('success', 'Account created! Please login.');
    }

    /**
     * Handle login - supports all user types (student, teacher, admin)
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // First, check in user_accounts table (covers all user types)
        $userAccount = UserAccount::where('username', $validated['username'])->first();

        if ($userAccount && Hash::check($validated['password'], $userAccount->password)) {
            // Store user in session
            session(['user' => $userAccount]);

            if ($userAccount->must_change_password) {
                return redirect()->route('password.change')->with('success', 'Please change your default password before continuing.');
            }
            
            // Redirect based on role
            return $this->redirectByRole($userAccount->role);
        }

        // Fallback: check in students table for legacy login
        $student = Student::where('username', $validated['username'])->first();

        if ($student && Hash::check($validated['password'], $student->password)) {
            // Create or update user account session
            session(['user' => $student]);

            $studentAccount = UserAccount::where('email', $student->email)->first();
            if ($studentAccount && $studentAccount->must_change_password) {
                return redirect()->route('password.change')->with('success', 'Please change your default password before continuing.');
            }
            
            return $this->redirectByRole($student->role ?? 'student');
        }

        return back()->withErrors(['username' => 'Invalid credentials']);
    }

    /**
     * Redirect user to appropriate dashboard based on role
     */
    public function redirectByRole(string $role)
    {
        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard')->with('success', 'Welcome, Admin! Login successful!');
            case 'teacher':
                return redirect('/teacher/dashboard')->with('success', 'Welcome, Teacher! Login successful!');
            case 'student':
                return redirect('/student/dashboard')->with('success', 'Welcome, Student! Login successful!');
            default:
                return redirect('/')->with('success', 'Login successful!');
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        session()->forget('user');
        session()->flush();
        
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
