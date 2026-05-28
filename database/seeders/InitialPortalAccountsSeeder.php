<?php

namespace Database\Seeders;

use App\Models\Degree;
use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialPortalAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bsit = Degree::firstOrCreate(['name' => 'BSIT']);

        Student::updateOrCreate(
            ['email' => 'jerson@gmail.com'],
            [
                'fname' => 'Jerson',
                'mname' => null,
                'lname' => 'Maningding',
                'contact' => null,
                'age' => 21,
                'degree_id' => $bsit->id,
                'username' => 'jerson',
                'password' => Hash::make('12345678'),
                'role' => 'student',
                'must_change_password' => true,
            ]
        );

        UserAccount::updateOrCreate(
            ['username' => 'jerson'],
            [
                'email' => 'jerson@gmail.com',
                'username' => 'jerson',
                'password' => Hash::make('12345678'),
                'must_change_password' => true,
                'role' => 'student',
            ]
        );

        UserAccount::updateOrCreate(
            ['username' => 'naps'],
            [
                'email' => 'napoleon.hermoso@jmm.com',
                'username' => 'naps',
                'password' => Hash::make('12345678'),
                'must_change_password' => true,
                'role' => 'teacher',
            ]
        );
    }
}