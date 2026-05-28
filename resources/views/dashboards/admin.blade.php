@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h3 mb-4">Admin Dashboard</h1>

        {{-- Stats Section --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="card text-bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Total Students</h6>
                        <p class="display-6 mb-0">{{ $studentCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card text-bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Total Teachers</h6>
                        <p class="display-6 mb-0">{{ $teacherCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card text-bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Degrees</h6>
                        <p class="display-6 mb-0">{{ $degreeCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Actions --}}
        <div class="mb-4">
            <h2 class="h5 mb-3">Management</h2>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.create-student') }}" class="btn btn-primary">➕ Add Student</a>
                <a href="{{ route('admin.create-teacher') }}" class="btn btn-success">➕ Add Teacher</a>
                <a href="{{ route('degrees.create') }}" class="btn btn-warning">📚 Add Degree</a>
                <a href="{{ route('admin.students-list') }}" class="btn btn-outline-info">👥 View Students</a>
                <a href="{{ route('admin.teachers-list') }}" class="btn btn-outline-info">👥 View Teachers</a>
                <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">View Courses</a>
                <a href="{{ route('eloquent.relationship') }}" class="btn btn-outline-primary">View Student Courses</a>
            </div>
        </div>

    </div>
</div>
@endsection
