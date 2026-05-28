<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('user_accounts', 'role')) {
            Schema::table('user_accounts', function (Blueprint $table) {
                $table->enum('role', ['student', 'teacher', 'admin'])->default('student')->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('user_accounts', 'role')) {
            Schema::table('user_accounts', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
