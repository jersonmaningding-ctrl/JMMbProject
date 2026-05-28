@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 mb-1">Student Dashboard</h1>
                <p class="text-muted mb-0">View your profile and enrollment details.</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Your Profile</h2>
                        <p class="mb-1"><strong>Full Name:</strong> {{ $student->full_name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ session('user')->email }}</p>
                        <p class="mb-1"><strong>Username:</strong> {{ session('user')->username }}</p>
                        @if($student)
                            <p class="mb-1"><strong>Degree:</strong> {{ $student->degree->name ?? 'Not assigned' }}</p>
                            <p class="mb-1"><strong>Age:</strong> {{ $student->age ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Contact:</strong> {{ $student->contact ?? 'N/A' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 bg-light">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Quick Stats</h2>
                        <p class="mb-2"><span class="fw-bold text-primary">0</span> Assignments Pending</p>
                        <p class="mb-0"><span class="fw-bold text-secondary">0</span> Grades Posted</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="h5 mb-3">Quick Actions</h2>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-primary btn-sm">View Assignments</a>
                <a href="#" class="btn btn-success btn-sm">View Grades</a>
                <a href="#" class="btn btn-outline-secondary btn-sm">Contact Teacher</a>
            </div>
        </div>
    </div>
</div>
@endsection
