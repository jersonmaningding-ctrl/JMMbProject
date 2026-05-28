<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Show the password change form.
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Update the password for the current user.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $sessionUser = session('user');
        $account = UserAccount::where('username', $sessionUser->username)->first();

        if (!$account) {
            return redirect('/login')->with('error', 'Account not found. Please log in again.');
        }

        if (!Hash::check($validated['current_password'], $account->password)) {
            return redirect()->back()->withInput()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $account->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        if ($sessionUser->role === 'student') {
            Student::where('email', $sessionUser->email)->update([
                'password' => $account->password,
                'must_change_password' => false,
            ]);
        }

        session(['user' => $account->fresh()]);

        return $this->redirectByRole($account->role)->with('success', 'Password updated successfully!');
    }

    /**
     * Redirect user to appropriate dashboard based on role
     */
    public function redirectByRole(string $role)
    {
        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'teacher':
                return redirect('/teacher/dashboard');
            case 'student':
                return redirect('/student/dashboard');
            default:
                return redirect('/');
        }
    }
}
