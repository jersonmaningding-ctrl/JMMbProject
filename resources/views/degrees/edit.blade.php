@extends('layouts.app')
@section('title', 'Edit Degree')
@section('content')
<div class="page-header">
    <div><h1>Edit Degree</h1><p>Update degree information</p></div>
    <a href="{{ route('degrees.index') }}" class="btn btn-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('degrees.update', $degree) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="name">Degree Title</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $degree->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem">
                <button type="submit" class="btn btn-warning">Update Degree</button>
                <a href="{{ route('degrees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
