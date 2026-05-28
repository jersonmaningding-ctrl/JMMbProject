@extends('layouts.app')
@section('title', 'Add Student')
@section('content')
<div class="page-header">
    <div><h1>Add New Student</h1><p>Fill in the student's information below</p></div>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf
            <div class="form-row-3">
                <div class="form-group">
                    <label for="fname">First Name *</label>
                    <input type="text" name="fname" id="fname" class="form-control @error('fname') is-invalid @enderror"
                        value="{{ old('fname') }}" placeholder="First name" required>
                    @error('fname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="mname">Middle Name</label>
                    <input type="text" name="mname" id="mname" class="form-control"
                        value="{{ old('mname') }}" placeholder="Middle name">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name *</label>
                    <input type="text" name="lname" id="lname" class="form-control @error('lname') is-invalid @enderror"
                        value="{{ old('lname') }}" placeholder="Last name" required>
                    @error('lname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="email@example.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="text" name="contact" id="contact" class="form-control"
                        value="{{ old('contact') }}" placeholder="09XXXXXXXXX">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age *</label>
                    <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror"
                        value="{{ old('age') }}" min="1" required>
                    @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="degree_id">Degree Program</label>
                    <select name="degree_id" id="degree_id" class="form-control">
                        <option value="">-- Select Degree --</option>
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>
                                {{ $degree->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username') }}" placeholder="john.doe123" required>
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter a secure password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1rem">
                <button type="submit" class="btn btn-success">Save Student</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
