@extends('layouts.app')
@section('title', 'Add Degree')
@section('content')
<div class="page-header">
    <div><h1>Add New Degree</h1><p>Create a new degree program</p></div>
    <a href="{{ route('degrees.index') }}" class="btn btn-secondary">← Back</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('degrees.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Degree Title</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="e.g. BSIT" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem">
                <button type="submit" class="btn btn-success">Save Degree</button>
                <a href="{{ route('degrees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
