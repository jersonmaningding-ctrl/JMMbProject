<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Degree;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            AdminUserSeeder::class,
        ]);

        // Add degree programs
        $degreeLookup = [];
        $degreeNames = [
            'HM',
            'BSIT',
            'BSOA',
            'BSBA',
            'BSAI',
            'BSABM',
        ];

        foreach ($degreeNames as $degreeName) {
            $degree = Degree::create(['name' => $degreeName]);
            $degreeLookup[$degreeName] = $degree->id;
        }

        // Add sample students
        $students = [
            [
                'fname' => 'Cris',
                'mname' => 'Ramos',
                'lname' => 'Catugas',
                'email' => 'cris@gmail.com',
                'contact' => '09949941083',
                'age' => 21,
                'degree_id' => $degreeLookup['BSIT'],
                'username' => 'cris.catugas',
                'password' => Hash::make('password123'),
            ],
            [
                'fname' => 'Jayson',
                'mname' => 'Castro',
                'lname' => 'Ramos',
                'email' => 'jayson@gmail.com',
                'contact' => '09991238765',
                'age' => 21,
                'degree_id' => $degreeLookup['BSABM'],
                'username' => 'jayson.ramos',
                'password' => Hash::make('password123'),
            ],
            [
                'fname' => 'Steven',
                'mname' => 'Lee',
                'lname' => 'Caguioa',
                'email' => 'steve@gmail.com',
                'contact' => '09949942011',
                'age' => 20,
                'degree_id' => $degreeLookup['BSAI'],
                'username' => 'steven.caguioa',
                'password' => Hash::make('password123'),
            ],
            [
                'fname' => 'James',
                'mname' => 'Stan',
                'lname' => 'Lee',
                'email' => 'james@gmail.com',
                'contact' => '09949942020',
                'age' => 21,
                'degree_id' => $degreeLookup['BSOA'],
                'username' => 'james.lee',
                'password' => Hash::make('password123'),
            ],
            [
                'fname' => 'Napoleon',
                'mname' => 'Camus',
                'lname' => 'Camus',
                'email' => 'naps@gmail.com',
                'contact' => '09167890456',
                'age' => 20,
                'degree_id' => $degreeLookup['BSIT'],
                'username' => 'naps123',
                'password' => Hash::make('naps12345'),
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->call([
            InitialPortalAccountsSeeder::class,
            StudentCourseSeeder::class,
        ]);
    }
}
