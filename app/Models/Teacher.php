<?php

namespace App\Models;

class Teacher extends UserAccount
{
    protected $table = 'user_accounts';

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseStudent::class, 'teacher_id');
    }
}
