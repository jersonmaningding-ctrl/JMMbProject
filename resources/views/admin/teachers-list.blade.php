@extends('layouts.app')

@section('title', 'Teachers List')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Teachers List</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.create-teacher') }}" class="btn btn-success">➕ Add Teacher</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td><small class="text-muted">{{ $teacher->id }}</small></td>
                        <td><strong>{{ $teacher->email }}</strong></td>
                        <td>{{ $teacher->username }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($teacher->role) }}</span></td>
                        <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No teachers found. <a href="{{ route('admin.create-teacher') }}">Add a teacher</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
