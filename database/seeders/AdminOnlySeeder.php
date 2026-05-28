<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminOnlySeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@jmm.com');
        $username = env('ADMIN_USERNAME', 'admin');
        $password = env('ADMIN_PASSWORD', 'admin123');

        UserAccount::updateOrCreate(
            ['username' => $username],
            [
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => 'admin',
                'must_change_password' => false,
            ]
        );

        $this->command?->info("Admin account ready: {$username} / {$email}");
    }
}