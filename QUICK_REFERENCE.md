# Quick Reference - Authentication Testing

## Admin Account (Pre-created)
```
URL:      http://localhost:8000/login
Username: admin
Password: admin123
Dashboard: http://localhost:8000/admin/dashboard
```

## Test Student Account
```
Username: student1
Password: password123
Dashboard: http://localhost:8000/student/dashboard
```

## Test Teacher Account
```
Username: teacher1
Password: password123
Dashboard: http://localhost:8000/teacher/dashboard
```

---

## Creating Test Accounts (As Admin)

### To Create a Student:
1. Login as admin
2. Click "Add Student" button on dashboard
3. Fill in all required fields (*)
4. Set username and password
5. Click "Add Student"
6. Logout and login with new student credentials

### To Create a Teacher:
1. Login as admin
2. Click "Add Teacher" button on dashboard
3. Fill in all required fields
4. Click "Add Teacher"
5. Logout and login with new teacher credentials

---

## Route Testing Quick Map

### Public Routes (No Login Required)
```
GET  /login              - Login page
GET  /register           - Registration page
POST /login              - Process login
POST /register           - Process registration
POST /logout             - Process logout (requires login)
```

### Student Routes (Students Only)
```
GET  /student/dashboard  - Student dashboard
```

### Teacher Routes (Teachers & Admins)
```
GET  /teacher/dashboard  - Teacher dashboard
```

### Admin Routes (Admins Only)
```
GET  /admin/dashboard           - Admin dashboard
GET  /admin/student/create      - Create student form
POST /admin/student/store       - Store student
GET  /admin/teacher/create      - Create teacher form
POST /admin/teacher/store       - Store teacher
```

---

## Session Keys

After successful login:
```php
session('user')              // Contains the user object
session('user')->role        // 'student', 'teacher', or 'admin'
session('user')->email       // User email
session('user')->username    // Username
```

---

## Database Query Reference

### View All Users
```sql
SELECT * FROM user_accounts;
SELECT * FROM students;
```

### View Users by Role
```sql
SELECT * FROM user_accounts WHERE role = 'admin';
SELECT * FROM user_accounts WHERE role = 'teacher';
SELECT * FROM user_accounts WHERE role = 'student';
```

### Check Specific User
```sql
SELECT * FROM user_accounts WHERE username = 'admin';
```

---

## Logout Behavior

When user clicks Logout:
1. Session data cleared completely
2. Redirected to: `/login`
3. Message displays: "Logged out successfully!"
4. All protected routes become inaccessible
5. Attempting to access dashboard → redirected to login

---

## Error Scenarios & Fixes

### Issue: Login fails
**Fix**: Check credentials are correct, user exists in `user_accounts` table

### Issue: Can't access dashboard after login
**Fix**: Check user's role field in database matches route requirements

### Issue: Logout doesn't work
**Fix**: Ensure session middleware is enabled in `config/session.php`

### Issue: "Access denied" message
**Fix**: Check that user role matches required role for the route

---

## Browser Developer Tools Tips

**Check Session Data:**
In browser console after login:
```javascript
// Check session cookie (varies by browser)
document.cookie
```

**Check Network Requests:**
1. Open DevTools → Network tab
2. Login and observe POST request to `/login`
3. Check Response headers for Set-Cookie
4. Click logout and check POST to `/logout`

---

## Files to Monitor During Testing

**Logs** (check for errors):
```
storage/logs/laravel.log
```

**Session Files** (if using file-based sessions):
```
storage/framework/sessions/
```

**Database**:
```
user_accounts table
students table
```

---

## Common Test Flows

### Flow 1: Admin Creates Users
1. Login as admin
2. Add student (John Doe)
3. Add teacher (Jane Smith)
4. Logout
5. Login as John Doe (student)
6. Verify student dashboard
7. Logout
8. Login as Jane Smith (teacher)
9. Verify teacher dashboard

### Flow 2: Authorization Testing
1. Login as student
2. Try to access: `/admin/dashboard`
   → Should redirect to login with error
3. Try to access: `/teacher/dashboard`
   → Should redirect to login with error
4. Try to access: `/student/dashboard`
   → Should show student dashboard

### Flow 3: Session Handling
1. Login as admin
2. Open developer tools → Storage → Cookies
3. Find session cookie (LARAVEL_SESSION)
4. Verify PHPSESSID or similar exists
5. Logout
6. Verify cookie is cleared

---

## Performance Check

After implementation, verify:
✓ Login completes in < 1 second
✓ Dashboard loads in < 2 seconds
✓ No database errors in logs
✓ Session file size reasonable
✓ No memory leaks after multiple logins/logouts

---

## Security Verification Checklist

Before going to production:
- [ ] All passwords hashed in database
- [ ] CSRF tokens on all forms
- [ ] Session driver configured
- [ ] .env file has correct APP_KEY
- [ ] SESSION_DRIVER is 'file' or 'database'
- [ ] storage directory is writable
- [ ] HTTPS enabled (if in production)
- [ ] Security headers configured

---

**Last Updated**: May 17, 2026
