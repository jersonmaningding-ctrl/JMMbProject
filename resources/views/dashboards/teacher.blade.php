@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 mb-1">Teacher Dashboard</h1>
                <p class="text-muted mb-0">Manage BSIT students and student records.</p>
            </div>
            <a href="{{ route('teacher.create-student') }}" class="btn btn-success">Add Student</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Your Profile</h6>
                        <p class="mb-1"><strong>Email:</strong> {{ session('user')->email }}</p>
                        <p class="mb-1"><strong>Username:</strong> {{ session('user')->username }}</p>
                        <p class="mb-0"><strong>Role:</strong> Teacher</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Statistics</h6>
                        <p class="display-6 mb-0">{{ count($bsitStudents) }}</p>
                        <small class="text-muted">BSIT students</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.create-student') }}" class="btn btn-outline-primary btn-sm">Add Student</a>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-sm">View Courses</a>
                            <a href="{{ route('eloquent.relationship') }}" class="btn btn-outline-primary btn-sm">View Student Courses</a>
                            <a href="#student-directory" class="btn btn-outline-secondary btn-sm">View Directory</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="student-directory" class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">BSIT Student Directory</h2>
                <span class="badge bg-primary">{{ count($bsitStudents) }} students</span>
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Degree</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bsitStudents as $student)
                            <tr>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->degree->name ?? 'N/A' }}</td>
                                <td>{{ $student->age ?? 'N/A' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary me-2">View</a>
                                    <a href="#" class="btn btn-sm btn-outline-success">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No BSIT students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
