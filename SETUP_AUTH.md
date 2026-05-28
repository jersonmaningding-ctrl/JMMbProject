# JMM Student Portal - Authentication & Authorization Setup Guide

## Overview
This document provides instructions for setting up and testing the implemented authentication system with role-based access control for Students, Teachers, and Admins.

## System Components

### User Roles
- **Student**: Can view their dashboard, profile, and assignments
- **Teacher**: Can view teacher dashboard and manage student grades
- **Admin**: Can view admin dashboard, add/manage students and teachers

### File Structure
```
app/Http/
├── Controllers/
│   ├── AuthController.php (handles login, register, logout)
│   ├── StudentDashboardController.php
│   ├── TeacherDashboardController.php
│   └── AdminDashboardController.php
├── Middleware/
│   ├── AuthUser.php (any authenticated user)
│   ├── AuthStudent.php (student role only)
│   ├── AuthTeacher.php (teacher or admin)
│   └── AuthAdmin.php (admin role only)
└── Models/
    ├── User.php
    ├── Student.php
    └── UserAccount.php (stores username, email, password, role)

resources/views/
├── dashboards/
│   ├── student.blade.php
│   ├── teacher.blade.php
│   └── admin.blade.php
├── admin/
│   ├── create-student.blade.php
│   └── create-teacher.blade.php
└── layouts/
    └── app.blade.php (updated navbar with role-based links)

database/migrations/
├── 2026_05_17_000000_add_role_to_user_accounts_table.php
└── 2026_05_17_000001_add_role_to_students_table.php

routes/
└── web.php (updated with all dashboard routes)
```

## Setup Instructions

### 1. Run Database Migrations
```bash
php artisan migrate
```

This will:
- Create the `user_accounts` table with `role` column
- Add `role` column to `students` table

### 2. Create Initial Admin (Manual Method)

Option A - Using Tinker:
```bash
php artisan tinker
```

Then execute:
```php
$admin = App\Models\UserAccount::create([
    'email' => 'admin@jmm.com',
    'username' => 'admin',
    'password' => Hash::make('admin123'),
    'role' => 'admin'
]);
```

Option B - Database Query:
```sql
INSERT INTO user_accounts (email, username, password, role, created_at, updated_at)
VALUES ('admin@jmm.com', 'admin', '$2y$12$...', 'admin', NOW(), NOW());
```
(Note: Use Laravel's Hash to generate the password hash)

### 3. Start Development Server
```bash
php artisan serve
```

## Testing the System

### Test Case 1: Admin Login & Create Users
1. Navigate to `http://localhost:8000/login`
2. Login with credentials:
   - Username: `admin`
   - Password: `admin123`
3. Should redirect to `/admin/dashboard`
4. Click "Add Student" button
5. Fill in student details and create
6. Verify student appears in "Students List"
7. Click "Add Teacher" button
8. Fill in teacher details and create
9. Verify teacher appears in "Teachers List"

### Test Case 2: Student Registration & Login
1. Navigate to `http://localhost:8000/register`
2. Fill in registration form:
   - Email: `student1@example.com`
   - Username: `student1`
   - Password: `password123`
3. Click "Register"
4. Should redirect to login page with success message
5. Login with new credentials
6. Should redirect to `/student/dashboard`
7. Verify student dashboard shows profile information

### Test Case 3: Teacher Access
1. Login with teacher account (created by admin)
2. Should redirect to `/teacher/dashboard`
3. Verify student list is displayed
4. Navbar should show "Dashboard" link for teacher

### Test Case 4: Logout Functionality
1. Login as any user
2. Click "Logout" button in navbar
3. Should redirect to login page
4. Session should be cleared
5. Attempting to access protected routes should redirect to login

### Test Case 5: Access Control (Negative Tests)
1. Login as student
2. Try to access `/admin/dashboard` or `/teacher/dashboard`
3. Should redirect to login with error message

1. Logout
2. Try to access `/student/dashboard` directly
3. Should redirect to login

## Key Session Variables

The application uses the following session structure:

```php
// After successful login
session('user') // Contains UserAccount or Student object
session('user')->role // Returns: 'student', 'teacher', or 'admin'
session('user')->email
session('user')->username
```

## Logout Implementation

The logout is handled via:
```html
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>
```

This:
1. Clears the 'user' session key
2. Flushes entire session
3. Redirects to login page with success message

## Security Notes

1. **Passwords**: All passwords are hashed using Laravel's Hash facade (bcrypt)
2. **Session**: Uses Laravel's built-in session handling with CSRF protection
3. **Middleware**: Role-based middleware prevents unauthorized access
4. **Form Submission**: Logout uses POST with CSRF token (not GET)

## Troubleshooting

### Issue: Login fails with "Invalid credentials"
- Ensure the user exists in `user_accounts` table
- Verify password is correct (case-sensitive)
- Check that role is set correctly

### Issue: Redirects to login after selecting role
- Verify the route exists and is correctly defined
- Check middleware registration in `bootstrap/app.php`
- Ensure session middleware is enabled

### Issue: "Access denied" messages
- Verify user role matches the required role
- Check that middleware is properly applied to the route

### Issue: Session data not persisting
- Check that `SESSION_DRIVER` in `.env` is set to `file` or `database`
- Ensure `storage/framework/sessions` directory is writable
- Run `php artisan cache:clear && php artisan config:clear`

## Next Steps (Future Enhancements)

1. Add email verification for registration
2. Add "Remember Me" functionality
3. Implement password reset
4. Add user profile editing
5. Add logging of authentication events
6. Implement two-factor authentication (2FA)
7. Add role-based view permissions matrix
8. Add course management and enrollment
