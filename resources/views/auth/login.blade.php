@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 2rem auto;">
    <div class="card">
        <div class="card-body">
            <h2 style="margin-bottom: 1.5rem; color: #1a202c;">Login to Your Account</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login</button>
            </form>

            <p style="text-align: center; margin-top: 1.5rem; color: #718096;">
                Don't have an account? <a href="{{ route('auth.register') }}" style="color: #3182ce; text-decoration: none; font-weight: 600;">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection
