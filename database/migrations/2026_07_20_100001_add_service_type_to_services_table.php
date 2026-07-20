<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('service_type')->default('website_pakket')->after('slug');
        });

        // Update existing services based on title hints
        \DB::table('services')->where('title', 'like', '%hosting%')->update(['service_type' => 'hosting']);
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
};
