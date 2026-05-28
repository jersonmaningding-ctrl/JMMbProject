@extends('layouts.app')

@section('title', 'Students List')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Students List</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.create-student') }}" class="btn btn-primary">➕ Add Student</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.students-list') }}" method="GET" class="d-flex gap-2 align-items-end">
                <div class="flex-grow-1">
                    <label for="degree_filter" class="form-label">Filter by Degree:</label>
                    <select id="degree_filter" name="degree_id" class="form-select">
                        <option value="">-- All Degrees --</option>
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ $degree_id == $degree->id ? 'selected' : '' }}>
                                {{ $degree->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                @if($degree_id)
                    <a href="{{ route('admin.students-list') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Degree</th>
                    <th>Age</th>
                    <th>Contact</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><small class="text-muted">{{ $student->id }}</small></td>
                        <td><strong>{{ $student->full_name }}</strong></td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->degree->name ?? 'N/A' }}</td>
                        <td>{{ $student->age ?? 'N/A' }}</td>
                        <td>{{ $student->contact ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary">{{ ucfirst($student->role) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No students found. <a href="{{ route('admin.create-student') }}">Add a student</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
