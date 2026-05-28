@extends('layouts.app')

@section('title', 'Eloquent Relationship Output')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Student Course Relationship</h1>
            <p class="text-muted mb-0">Recent students with their assigned courses.</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Back to Students</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    @forelse ($student->courses as $course)
                        <tr>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $course->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>{{ $student->full_name }}</td>
                            <td class="text-muted">No course assigned</td>
                        </tr>
                    @endforelse
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
