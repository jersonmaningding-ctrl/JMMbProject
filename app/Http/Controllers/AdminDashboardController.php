<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UserAccount;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $user = session('user');
        $students = Student::all();
        $teachers = UserAccount::where('role', 'teacher')->get();
        $studentCount = Student::count();
        $teacherCount = UserAccount::where('role', 'teacher')->count();
        $degrees = Degree::all();
        $degreeCount = Degree::count();
        
        return view('dashboards.admin', compact('user', 'students', 'teachers', 'studentCount', 'teacherCount', 'degrees', 'degreeCount'));
    }

    /**
     * Show form to add a student
     */
    public function createStudent()
    {
        return view('admin.create-student');
    }

    /**
     * Store a new student
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            // allow creating a student even if a user account exists; student email must be unique among students
            'email' => 'required|email|unique:students,email',
            'contact' => 'nullable|string',
            'age' => 'nullable|integer',
            'degree_id' => 'nullable|exists:degrees,id',
            'username' => 'required|string|unique:user_accounts,username',
            'password' => 'required|min:6|confirmed',
        ]);

        // Prevent duplicate email/username across students and user_accounts
        if (Student::where('email', $validated['email'])->exists() || UserAccount::where('email', $validated['email'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'The provided email is already in use.');
        }

        if (UserAccount::where('username', $validated['username'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'The provided username is already in use.');
        }

        $password = Hash::make($validated['password']);

        try {
            DB::beginTransaction();

            $student = Student::create([
                'fname' => $validated['fname'],
                'mname' => $validated['mname'],
                'lname' => $validated['lname'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
                'age' => $validated['age'],
                'degree_id' => $validated['degree_id'],
                'username' => $validated['username'],
                'password' => $password,
                'role' => 'student',
                'must_change_password' => true,
            ]);
            // Student model's created hook will create the UserAccount if missing

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create student: '.$e->getMessage());
        }

        return redirect()->route('admin.dashboard')->with('success', 'Student added successfully!');
    }

    /**
     * Show form to add a teacher
     */
    public function createTeacher()
    {
        return view('admin.create-teacher');
    }

    /**
     * Store a new teacher
     */
    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:user_accounts,email',
            'username' => 'required|string|unique:user_accounts,username',
            'password' => 'required|min:6|confirmed',
        ]);

        // Double-check duplicates (friendly error) before creation
        if (UserAccount::where('email', $validated['email'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'The provided email is already in use.');
        }
        if (UserAccount::where('username', $validated['username'])->exists()) {
            return redirect()->back()->withInput()->with('error', 'The provided username is already in use.');
        }

        try {
            UserAccount::create([
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'must_change_password' => true,
                'role' => 'teacher',
            ]);
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create teacher: '.$e->getMessage());
        }

        return redirect()->route('admin.dashboard')->with('success', 'Teacher added successfully!');
    }

    /**
     * Display all students in a separate page with optional degree filter
     */
    public function studentsList(Request $request)
    {
        $degree_id = $request->query('degree_id');
        $degrees = Degree::all();
        
        if ($degree_id) {
            $students = Student::where('degree_id', $degree_id)->get();
        } else {
            $students = Student::all();
        }
        
        return view('admin.students-list', compact('students', 'degrees', 'degree_id'));
    }

    /**
     * Display all teachers in a separate page
     */
    public function teachersList()
    {
        $teachers = UserAccount::where('role', 'teacher')->get();
        return view('admin.teachers-list', compact('teachers'));
    }
}
