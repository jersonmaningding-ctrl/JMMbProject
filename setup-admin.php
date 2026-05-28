<?php
// This file sets up an initial admin user
// Run with: php setup-admin.php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

// Check if admin already exists
$existingAdmin = UserAccount::where('username', 'admin')->first();

if ($existingAdmin) {
    echo "Admin user already exists!\n";
    echo "Email: {$existingAdmin->email}\n";
    echo "Username: {$existingAdmin->username}\n";
    echo "Role: {$existingAdmin->role}\n";
} else {
    $admin = UserAccount::create([
        'email' => 'admin@jmm.com',
        'username' => 'admin',
        'password' => Hash::make('admin123'),
        'role' => 'admin'
    ]);

    echo "✓ Admin user created successfully!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Email:    {$admin->email}\n";
    echo "Username: {$admin->username}\n";
    echo "Password: admin123\n";
    echo "Role:     {$admin->role}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
}
