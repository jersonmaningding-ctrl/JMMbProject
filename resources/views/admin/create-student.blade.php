@extends('layouts.app')

@section('title', 'Add Student')

@section('content')
@php
    $formAction = $formAction ?? 'admin.store-student';
    $backRoute = $backRoute ?? 'admin.dashboard';
    $pageTitle = $pageTitle ?? 'Add New Student';
    $pageSubtitle = $pageSubtitle ?? 'Create a new student account and assign degree';
@endphp
<div class="container py-5">
    <div class="card mx-auto" style="max-width:900px;">
        <div class="card-body">
            <div class="page-header mb-4">
                <h1 class="h2">{{ $pageTitle }}</h1>
                <p class="mb-0 text-muted">{{ $pageSubtitle }}</p>
            </div>
        
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Validation Errors</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route($formAction) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fname" class="form-label">First Name *</label>
                        <input type="text" id="fname" name="fname" value="{{ old('fname') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mname" class="form-label">Middle Name</label>
                        <input type="text" id="mname" name="mname" value="{{ old('mname') }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lname" class="form-label">Last Name *</label>
                        <input type="text" id="lname" name="lname" value="{{ old('lname') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" id="age" name="age" value="{{ old('age') }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="tel" id="contact" name="contact" value="{{ old('contact') }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="degree_id" class="form-label">Degree</label>
                        <select id="degree_id" name="degree_id" class="form-select">
                            <option value="">-- Select a Degree --</option>
                            @forelse(\App\Models\Degree::all() as $degree)
                                <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>
                                    {{ $degree->name }}
                                </option>
                            @empty
                                <option disabled>No degrees available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Add Student</button>
                    <a href="{{ route($backRoute) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
    </div>
</div>
@endsection
