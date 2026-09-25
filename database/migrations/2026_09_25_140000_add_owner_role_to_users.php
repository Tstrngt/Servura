<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('owner','admin','employee','customer') NOT NULL DEFAULT 'customer'");
        }
        DB::table('users')->where('role', 'admin')->update(['role' => 'owner']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'owner')->update(['role' => 'admin']);
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin','employee','customer') NOT NULL DEFAULT 'customer'");
        }
    }
};
