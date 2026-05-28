@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome to your personal dashboard</p>
    </div>
</div>

@if (session('user'))
    <div class="alert alert-success">
        ✅ Welcome back, <strong>{{ session('user')->email }}</strong>!
    </div>
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-body">
            <h3 style="color: #1a202c; margin-bottom: 1rem;">Account Information</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Email Address</label>
                    <p>{{ session('user')->email }}</p>
                </div>
                <div class="detail-item">
                    <label>Account Created</label>
                    <p>{{ session('user')->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">
        ⚠️ Please <a href="{{ route('auth.login') }}" style="color: #9b2c2c; font-weight: 600; text-decoration: underline;">login</a> to access the dashboard.
    </div>
@endif
@endsection
