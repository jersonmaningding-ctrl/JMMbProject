<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('user_accounts')->nullOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'student_id']);
        });

        if (Schema::hasTable('course_student')) {
            DB::table('course_student')
                ->join('courses', 'course_student.course_id', '=', 'courses.id')
                ->select([
                    'course_student.course_id',
                    'course_student.student_id',
                    'courses.teacher_id',
                    'course_student.created_at',
                    'course_student.updated_at',
                ])
                ->orderBy('course_student.id')
                ->chunk(100, function ($rows) {
                    foreach ($rows as $row) {
                        DB::table('course_students')->updateOrInsert(
                            [
                                'course_id' => $row->course_id,
                                'student_id' => $row->student_id,
                            ],
                            [
                                'teacher_id' => $row->teacher_id,
                                'created_at' => $row->created_at,
                                'updated_at' => $row->updated_at,
                            ]
                        );
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_students');
    }
};
