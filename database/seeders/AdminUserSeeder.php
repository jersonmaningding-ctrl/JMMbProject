<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = 'admin';
        $email = 'admin@gmail.com';
        $password = 'admin321';

        $existing = UserAccount::where('username', $username)
            ->orWhere('email', $email)
            ->first();

        $data = [
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 'admin',
            'must_change_password' => false,
        ];

        if ($existing) {
            $existing->update($data);

            $this->command?->info('Admin user already exists. Updated admin credentials.');
            return;
        }

        UserAccount::create($data);

        $this->command?->info("Admin user created: {$username} / {$email} (password: {$password})");
    }
}