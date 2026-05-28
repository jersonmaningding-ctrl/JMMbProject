@extends('layouts.app')

@section('title', $course->name)

@section('content')
    <section class="course-shell">
        <div class="course-hero">
            <div>
                <span class="course-kicker">Course Enrollment</span>
                <h1>{{ $course->name }}</h1>
                <div class="teacher-pill">
                    <span class="teacher-avatar">{{ strtoupper(substr($course->teacher->username ?? 'T', 0, 1)) }}</span>
                    <div>
                        <strong>{{ $course->teacher->username ?? 'No teacher assigned' }}</strong>
                        <small>{{ $course->teacher->email ?? 'Teacher account unavailable' }}</small>
                    </div>
                </div>
            </div>

            <div class="course-hero-actions">
                <div class="course-count">
                    <strong>{{ $course->students->count() }}</strong>
                    <span>Students</span>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-light btn-sm">Back to Courses</a>
            </div>
        </div>

        <div class="course-board">
            <div class="course-board-head">
                <div>
                    <h2>Enrolled Students</h2>
                    <p>Manage who is enrolled in {{ $course->name }}.</p>
                </div>

                <form action="{{ route('courses.students.add', $course) }}" method="POST" class="course-add-form">
                    @csrf
                    <select name="student_id" id="student_id" class="form-select form-select-sm" required>
                        <option value="">Select student</option>
                        @foreach ($availableStudents as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->full_name }}{{ $student->email ? ' - '.$student->email : '' }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm" @disabled($availableStudents->isEmpty())>
                        Add Student
                    </button>
                </form>
            </div>

            <div class="table-responsive course-table-wrap">
                <table class="course-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Degree</th>
                            <th>Age</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($course->students as $student)
                            <tr>
                                <td data-label="Student">
                                    <div class="student-name-cell">
                                        <strong>{{ $student->full_name }}</strong>
                                    </div>
                                </td>
                                <td data-label="Email">{{ $student->email ?? 'N/A' }}</td>
                                <td data-label="Degree"><span class="mini-badge">{{ $student->degree->name ?? 'N/A' }}</span></td>
                                <td data-label="Age">{{ $student->age ?? 'N/A' }}</td>
                                <td data-label="Action" class="text-end">
                                    <form action="{{ route('courses.students.remove', [$course, $student]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No students enrolled in this course.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
