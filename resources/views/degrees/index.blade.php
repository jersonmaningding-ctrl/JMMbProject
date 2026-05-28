@extends('layouts.app')
@section('title', 'Degrees')
@section('content')
<div class="page-header">
    <div>
        <h1>Degrees</h1>
        <p>Manage all degree programs</p>
    </div>
    <a href="{{ route('degrees.create') }}" class="btn btn-primary">+ Add Degree</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Degree Name</th>
                    <th>Students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($degrees as $degree)
                <tr>
                    <td>{{ $degree->id }}</td>
                    <td><span class="badge badge-blue">{{ $degree->name }}</span></td>
                    <td>{{ $degree->students_count }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('degrees.show', $degree) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('degrees.edit', $degree) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('degrees.destroy', $degree) }}" method="POST" onsubmit="return confirm('Delete this degree?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4">
                    <div class="empty-state"><p>No degrees found. <a href="{{ route('degrees.create') }}">Add one</a>.</p></div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $degrees->links() }}</div>
</div>
@endsection
