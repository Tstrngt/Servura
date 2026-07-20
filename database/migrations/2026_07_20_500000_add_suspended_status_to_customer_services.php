<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE customer_services MODIFY COLUMN status ENUM('active', 'inactive', 'suspended', 'cancelled', 'expired') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE customer_services MODIFY COLUMN status ENUM('active', 'inactive', 'cancelled', 'expired') DEFAULT 'active'");
    }
};
