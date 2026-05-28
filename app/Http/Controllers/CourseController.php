<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Degree;
use App\Models\Student;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of all courses.
     */
    public function index()
    {
        $courses = Course::with(['degree', 'teacher'])->orderBy('name')->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * Display the selected course with enrolled students.
     */
    public function show(Course $course)
    {
        $course->load(['teacher', 'students.degree']);
        $availableStudents = Student::whereDoesntHave('courses', function ($query) use ($course) {
            $query->where('courses.id', $course->id);
        })
            ->orderBy('lname')
            ->orderBy('fname')
            ->get();

        return view('courses.show', compact('course', 'availableStudents'));
    }

    /**
     * Enroll a student in the selected course.
     */
    public function addStudent(Request $request, Course $course)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $course->students()->syncWithoutDetaching([
            $validated['student_id'] => [
                'teacher_id' => $course->teacher_id,
            ],
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Student added to course successfully.');
    }

    /**
     * Remove a student from the selected course.
     */
    public function removeStudent(Course $course, Student $student)
    {
        $course->students()->detach($student->id);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Student removed from course successfully.');
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $degrees = Degree::all();
        return view('courses.create', compact('degrees'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:courses,name',
            'degree_id' => 'required|exists:degrees,id',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'Course created successfully!');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $degrees = Degree::all();
        return view('courses.edit', compact('course', 'degrees'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:courses,name,' . $course->id,
            'degree_id' => 'required|exists:degrees,id',
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }
}
