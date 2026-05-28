@extends('layouts.app')
@section('title', 'Student Details')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ $student->fname }} {{ $student->lname }}</h1>
        <p>Student profile &amp; details</p>
    </div>
    <div style="display:flex;gap:.5rem">
        <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Student ID</label>
                <p>#{{ $student->id }}</p>
            </div>
            <div class="detail-item">
                <label>Full Name</label>
                <p>{{ $student->fname }} {{ $student->mname }} {{ $student->lname }}</p>
            </div>
            <div class="detail-item">
                <label>Email Address</label>
                <p>{{ $student->email ?? '—' }}</p>
            </div>
            <div class="detail-item">
                <label>Contact Number</label>
                <p>{{ $student->contact ?? '—' }}</p>
            </div>
            <div class="detail-item">
                <label>Age</label>
                <p>{{ $student->age }} years old</p>
            </div>
            <div class="detail-item">
                <label>Degree Program</label>
                <p>
                    @if($student->degree)
                        <span class="badge badge-blue">{{ $student->degree->name }}</span>
                    @else
                        <span class="badge badge-gray">Not Assigned</span>
                    @endif
                </p>
            </div>
            <div class="detail-item">
                <label>Date Added</label>
                <p>{{ $student->created_at->format('F d, Y') }}</p>
            </div>
            <div class="detail-item">
                <label>Last Updated</label>
                <p>{{ $student->updated_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
