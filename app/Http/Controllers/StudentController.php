<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Degree;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $degrees = Degree::all();
        $students = Student::with('degree')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'html' => view('students._table', compact('students'))->render(),
            ]);
        }

        return view('students.index', compact('students', 'degrees'));
    }

    public function create()
    {
        $degrees = Degree::all();
        return view('students.create', compact('degrees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname'     => 'required|string|max:255',
            'mname'     => 'nullable|string|max:255',
            'lname'     => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'contact'   => 'nullable|string|max:20',
            'age'       => 'required|integer|min:1',
            'degree_id' => 'nullable|exists:degrees,id',
            'username'  => 'required|string|unique:students,username|max:255',
            'password'  => 'required|string|min:6|max:255',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $student = Student::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Student added successfully.',
                'student' => $student->load('degree'),
            ], 201);
        }
        
        return redirect()->route('students.index')->with('success', 'Successfully add student');
    }

    public function show(Student $student)
    {
        $student->load('degree');

        if (request()->ajax()) {
            return response()->json($student);
        }

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $degrees = Degree::all();
        return view('students.edit', compact('student', 'degrees'));
    }

    public function update(Request $request, Student $student)
    {
        $originalEmail = $student->email;
        $originalUsername = $student->username;

        $validated = $request->validate([
            'fname'     => 'required|string|max:255',
            'mname'     => 'nullable|string|max:255',
            'lname'     => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'contact'   => 'nullable|string|max:20',
            'age'       => 'required|integer|min:1',
            'degree_id' => 'nullable|exists:degrees,id',
            'username'  => 'required|string|unique:students,username,'.$student->id.'|max:255',
            'password'  => 'nullable|string|min:6|max:255',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);
        $this->syncStudentAccount($student, $validated, $originalEmail, $originalUsername);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Student updated successfully.',
                'student' => $student->load('degree'),
            ]);
        }
        
        return redirect()->route('students.index')->with('success', 'Successfully save student');
    }

    private function syncStudentAccount(Student $student, array $validated, ?string $originalEmail, ?string $originalUsername): void
    {
        $accountQuery = UserAccount::query();
        $hasLookup = false;

        if (!empty($originalEmail)) {
            $accountQuery->where('email', $originalEmail);
            $hasLookup = true;
        }

        if (!empty($originalUsername)) {
            $hasLookup
                ? $accountQuery->orWhere('username', $originalUsername)
                : $accountQuery->where('username', $originalUsername);
            $hasLookup = true;
        }

        if (!$hasLookup) {
            return;
        }

        $account = $accountQuery->first();

        if (!$account) {
            return;
        }

        $accountData = [
            'email' => $student->email,
            'username' => $student->username,
            'role' => $student->role ?? 'student',
        ];

        if (isset($validated['password'])) {
            $accountData['password'] = $validated['password'];
        }

        $account->update($accountData);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        if (request()->ajax()) {
            return response()->json([
                'message' => 'Student deleted successfully.',
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
