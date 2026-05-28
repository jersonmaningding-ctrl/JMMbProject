<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    /**
     * Display student dashboard
     */
    public function dashboard()
    {
        $user = session('user');
        $student = Student::with('degree')
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email)
                    ->orWhere('username', $user->username);
            })
            ->first();
        
        return view('dashboards.student', compact('student'));
    }

    /**
     * Display the list of students.
     */
    public function index()
    {
        $students = Student::with('degree')->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'contact' => 'nullable|string',
            'age' => 'nullable|integer',
            'degree_id' => 'nullable|exists:degrees,id',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'fname' => 'required|string',
            'mname' => 'nullable|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'contact' => 'nullable|string',
            'age' => 'nullable|integer',
            'degree_id' => 'nullable|exists:degrees,id',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
