@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="page-header">
    <div>
        <h1>Students</h1>
        <p>Manage students</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">View Courses</a>
        <a href="{{ route('eloquent.relationship') }}" class="btn btn-outline-primary">View Student Courses</a>
        <button type="button" class="btn btn-primary" id="addStudentBtn">+ Add Student</button>
    </div>
</div>

<div
    id="studentsAjaxPage"
    data-index-url="{{ route('students.index') }}"
    data-store-url="{{ route('students.store') }}"
>
    <div id="studentAlert" class="alert" style="display: none;"></div>

    <div class="card" id="studentsTable">
        @include('students._table', ['students' => $students])
    </div>
</div>

<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="studentForm" action="{{ route('students.store') }}" method="POST" onsubmit="return submitStudentModalForm(event, this)">
                @csrf
                <input type="hidden" name="_method" id="studentMethod" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="ajax_fname">First Name *</label>
                            <input type="text" name="fname" id="ajax_fname" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="ajax_mname">Middle Name</label>
                            <input type="text" name="mname" id="ajax_mname" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="ajax_lname">Last Name *</label>
                            <input type="text" name="lname" id="ajax_lname" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ajax_email">Email Address</label>
                            <input type="email" name="email" id="ajax_email" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="ajax_contact">Contact Number</label>
                            <input type="text" name="contact" id="ajax_contact" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ajax_age">Age *</label>
                            <input type="number" name="age" id="ajax_age" class="form-control" min="1" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="ajax_degree_id">Degree Program</label>
                            <select name="degree_id" id="ajax_degree_id" class="form-control">
                                <option value="">-- Select Degree --</option>
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree->id }}">{{ $degree->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ajax_username">Username *</label>
                            <input type="text" name="username" id="ajax_username" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="ajax_password">Password</label>
                            <input type="password" name="password" id="ajax_password" class="form-control">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted" id="passwordHelp">Required for new students. Leave blank while editing to keep the current password.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveStudentBtn">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
