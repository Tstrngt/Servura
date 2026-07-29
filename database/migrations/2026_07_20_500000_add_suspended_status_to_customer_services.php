<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE customer_services MODIFY COLUMN status ENUM('active', 'inactive', 'suspended', 'cancelled', 'expired') DEFAULT 'active'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE customer_services MODIFY COLUMN status ENUM('active', 'inactive', 'cancelled', 'expired') DEFAULT 'active'");
    }
};
