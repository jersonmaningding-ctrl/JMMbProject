@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <section class="course-shell">
        <div class="course-list-header">
            <div>
                <span class="course-kicker">Course Directory</span>
                <h1>Courses</h1>
                <p>Select an elective to view the teacher and enrolled students.</p>
            </div>
        </div>

        <div class="course-grid">
            @forelse ($courses as $course)
                <a href="{{ route('courses.show', $course) }}" class="course-card-link">
                    <article class="course-card">
                        <div>
                            <span class="mini-badge">{{ $course->degree->name ?? 'N/A' }}</span>
                            <h2>{{ $course->name }}</h2>
                        </div>
                        <div class="course-card-meta">
                            <span>Teacher</span>
                            <strong>{{ $course->teacher->username ?? 'No teacher assigned' }}</strong>
                        </div>
                        <div class="course-card-footer">
                            <span>{{ $course->students()->count() }} students</span>
                            <strong>View</strong>
                        </div>
                    </article>
                </a>
            @empty
                <div class="course-board">
                    <p class="text-muted mb-0">No courses found.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
