<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_services', function (Blueprint $table) {
            $table->dropUnique('customer_services_user_id_service_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('customer_services', function (Blueprint $table) {
            $table->unique(['user_id', 'service_id']);
        });
    }
};
