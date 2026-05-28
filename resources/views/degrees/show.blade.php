@extends('layouts.app')
@section('title', 'Degree Details')
@section('content')
<div class="page-header">
    <div><h1>Degree: {{ $degree->name }}</h1><p>Degree program details</p></div>
    <div style="display:flex;gap:.5rem">
        <a href="{{ route('degrees.edit', $degree) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('degrees.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Degree ID</label>
                <p>{{ $degree->id }}</p>
            </div>
            <div class="detail-item">
                <label>Degree Name</label>
                <p><span class="badge badge-blue">{{ $degree->name }}</span></p>
            </div>
            <div class="detail-item">
                <label>Total Students</label>
                <p>{{ $degree->students->count() }}</p>
            </div>
            <div class="detail-item">
                <label>Courses</label>
                <p>
                    @forelse($degree->courses as $c)
                        <span class="badge badge-green">{{ $c->name }}</span>
                    @empty N/A
                    @endforelse
                </p>
            </div>
        </div>

        @if($degree->students->count())
        <hr style="margin:1.5rem 0;border:none;border-top:1px solid #e2e8f0">
        <h3 style="margin-bottom:1rem;font-size:1rem;color:#4a5568">Enrolled Students</h3>
        <table>
            <thead><tr><th>#</th><th>Full Name</th><th>Email</th><th>Contact</th></tr></thead>
            <tbody>
                @foreach($degree->students as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->fname }} {{ $s->lname }}</td>
                    <td>{{ $s->email ?? '—' }}</td>
                    <td>{{ $s->contact ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
