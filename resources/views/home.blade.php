@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="page-header">
    <div><h1>Welcome to JMM Student Portal</h1><p>Manage your academic information</p></div>
</div>
<div class="card">
    <div class="card-body">
        <div style="text-align: center; padding: 2rem;">
            <h2 style="color: #2d3748; margin-bottom: 1rem;">📚 Student Management System</h2>
            <p style="color: #718096; font-size: 1rem; margin-bottom: 2rem; line-height: 1.6;">
                Welcome to the JMM Student Portal. Use the navigation above to access the Students and Degrees sections.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('students.index') }}" class="btn btn-primary">View Students</a>
                <a href="{{ route('degrees.index') }}" class="btn btn-secondary">View Degrees</a>
                <a href="{{ route('students.create') }}" class="btn btn-success">+ Add Student</a>
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 2rem;">
    <h2 style="color: #2d3748; margin-bottom: 1rem;">📋 Recent Students</h2>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Age</th>
                        <th>Degree</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td><strong>{{ $student->lname }}</strong>, {{ $student->fname }} {{ $student->mname }}</td>
                        <td>{{ $student->email ?? '—' }}</td>
                        <td>{{ $student->contact ?? '—' }}</td>
                        <td>{{ $student->age }}</td>
                        <td>
                            @if($student->degree)
                                <span class="badge badge-blue">{{ $student->degree->name }}</span>
                            @else
                                <span class="badge badge-gray">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-secondary btn-sm">View</a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">
                        <div class="empty-state"><p>No students found. <a href="{{ route('students.create') }}">Add one</a>.</p></div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('students.index') }}" class="btn btn-primary">View All Students →</a>
    </div>
</div>
@endsection
