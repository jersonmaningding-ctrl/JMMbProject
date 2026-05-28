@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="card">
    <div class="card-body">
        <h1 class="h3 mb-4">Profile</h1>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Account</h2>
                        <p class="mb-1"><strong>Username:</strong> {{ $user->username ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Role:</strong> {{ ucfirst($user->role ?? 'user') }}</p>
                    </div>
                </div>
            </div>

            @if ($student)
                <div class="col-md-6">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h2 class="h5 mb-3">Student Details</h2>
                            <p class="mb-1"><strong>Name:</strong> {{ $student->full_name }}</p>
                            <p class="mb-1"><strong>Degree:</strong> {{ $student->degree->name ?? 'Not assigned' }}</p>
                            <p class="mb-1"><strong>Age:</strong> {{ $student->age ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Contact:</strong> {{ $student->contact ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
