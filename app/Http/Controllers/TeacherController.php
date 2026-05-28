<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TeacherController extends Controller
{
    /**
     * Display teacher dashboard
     */
    public function dashboard()
    {
        $user = session('user');
        $students = Student::with('degree')->get();
        $bsitStudents = Student::with('degree')
            ->whereHas('degree', function ($query) {
                $query->where('name', 'BSIT');
            })
            ->get();
        
        return view('dashboards.teacher', compact('user', 'students', 'bsitStudents'));
    }

    /**
     * Show form to add a student from the teacher dashboard.
     */
    public function createStudent()
    {
        return view('admin.create-student', [
            'formAction' => 'teacher.store-student',
            'backRoute' => 'teacher.dashboard',
            'pageTitle' => 'Add Student',
            'pageSubtitle' => 'Create a student account and assign a degree',
        ]);
    }

    /**
     * Store a new student from the teacher dashboard.
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'contact' => 'nullable|string',
            'age' => 'nullable|integer',
            'degree_id' => 'nullable|exists:degrees,id',
            'username' => 'required|string|unique:user_accounts,username',
            'password' => 'required|min:6|confirmed',
        ]);

        if (Student::where('email', $validated['email'])->exists() || UserAccount::where('email', $validated['email'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'The provided email is already in use.');
        }

        if (UserAccount::where('username', $validated['username'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'The provided username is already in use.');
        }

        try {
            DB::beginTransaction();

            Student::create([
                'fname' => $validated['fname'],
                'mname' => $validated['mname'],
                'lname' => $validated['lname'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
                'age' => $validated['age'],
                'degree_id' => $validated['degree_id'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'must_change_password' => true,
            ]);

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create student: '.$e->getMessage());
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Student added successfully!');
    }
}
