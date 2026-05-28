<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Models\Student;

Route::get('/eloquent-relationship', function () {
    $students = Student::with('courses')->latest()->get();

    return view('eloquent-relationship', compact('students'));
})->name('eloquent.relationship');

// Public routes - Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - require authentication
Route::middleware('auth.user')->group(function () {
    Route::get('/', function () {
        $user = session('user');
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    })->name('home');

    // Student routes
    Route::middleware('auth.student')->group(function () {
        Route::get('/student/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
    });

    // Teacher routes
    Route::middleware('auth.teacher')->group(function () {
        Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
        Route::get('/teacher/student/create', [TeacherController::class, 'createStudent'])->name('teacher.create-student');
        Route::post('/teacher/student/store', [TeacherController::class, 'storeStudent'])->name('teacher.store-student');
    });

    // Admin routes
    Route::middleware('auth.admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/students-list', [AdminDashboardController::class, 'studentsList'])->name('admin.students-list');
        Route::get('/admin/teachers-list', [AdminDashboardController::class, 'teachersList'])->name('admin.teachers-list');
        Route::get('/admin/student/create', [AdminDashboardController::class, 'createStudent'])->name('admin.create-student');
        Route::post('/admin/student/store', [AdminDashboardController::class, 'storeStudent'])->name('admin.store-student');
        Route::get('/admin/teacher/create', [AdminDashboardController::class, 'createTeacher'])->name('admin.create-teacher');
        Route::post('/admin/teacher/store', [AdminDashboardController::class, 'storeTeacher'])->name('admin.store-teacher');
    });

    // Password routes
    Route::get('/change-password', [PasswordController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');

    // General protected routes

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/profile', function () {
        $user = session('user');
        $student = null;

        if ($user->role === 'student') {
            $student = Student::with('degree')
                ->where('email', $user->email)
                ->orWhere('username', $user->username)
                ->first();
        }

        return view('profile', compact('user', 'student'));
    })->name('profile');

    Route::post('/courses/{course}/students', [CourseController::class, 'addStudent'])->name('courses.students.add');
    Route::delete('/courses/{course}/students/{student}', [CourseController::class, 'removeStudent'])->name('courses.students.remove');

    Route::resource('degrees', DegreeController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('students', StudentController::class);
});
