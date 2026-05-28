<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Degree;
use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Database\Seeder;

class StudentCourseSeeder extends Seeder
{
    public function run(): void
    {
        $degree = Degree::firstOrCreate(['name' => 'BSIT']);
        $teacher = UserAccount::where('role', 'teacher')->first();

        $courses = collect(['ELECTIVE 1', 'ELECTIVE 2', 'ELECTIVE 3', 'ELECTIVE 4', 'ELECTIVE 5'])
            ->mapWithKeys(function (string $courseName) use ($degree) {
                $course = Course::firstOrCreate(
                    ['name' => $courseName],
                    ['degree_id' => $degree->id]
                );

                return [$courseName => $course];
            });

        if ($teacher) {
            $courses->each(function (Course $course) use ($teacher) {
                $course->update(['teacher_id' => $teacher->id]);
            });
        }

        Course::whereIn('name', ['ELEC1', 'ELEC2', 'PROG1'])->delete();

        Student::latest()
            ->take(5)
            ->get()
            ->each(function (Student $student, int $index) use ($courses, $teacher) {
                $student->courses()->syncWithoutDetaching([
                    $courses['ELECTIVE 1']->id => [
                        'teacher_id' => $teacher?->id,
                    ],
                    $courses['ELECTIVE ' . (($index % 4) + 2)]->id => [
                        'teacher_id' => $teacher?->id,
                    ],
                ]);
            });
    }
}
