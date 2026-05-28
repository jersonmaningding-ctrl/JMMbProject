<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserAccount;

class Student extends Model
{
    protected $fillable = ['fname', 'mname', 'lname', 'email', 'contact', 'age', 'degree_id', 'username', 'password', 'role', 'must_change_password'];

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(CourseStudent::class, 'student_id');
    }

    public function account()
    {
        return $this->hasOne(UserAccount::class, 'username', 'username');
    }

    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->mname} {$this->lname}";
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($student) {
            if (!empty($student->email)) {
                // Avoid creating duplicate user accounts if one already exists
                if (!UserAccount::where('email', $student->email)->exists() && !UserAccount::where('username', $student->username)->exists()) {
                    UserAccount::create([
                        'email' => $student->email,
                        'username' => $student->username,
                        'password' => $student->password,
                        'must_change_password' => (bool) ($student->must_change_password ?? false),
                        'role' => 'student',
                    ]);
                }
            }
        });
    }
}
