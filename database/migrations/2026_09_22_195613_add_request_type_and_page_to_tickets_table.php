<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('request_type')->nullable()->after('category');
            $table->string('page')->nullable()->after('request_type');
            $table->text('customer_notes')->nullable()->after('page');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['request_type', 'page', 'customer_notes']);
        });
    }
};
