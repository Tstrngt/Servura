<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN category ENUM('technical', 'billing', 'general', 'feature_request', 'bug_report', 'offerte') DEFAULT 'general'");

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('ticket_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ticket_id');
        });

        DB::statement("ALTER TABLE tickets MODIFY COLUMN category ENUM('technical', 'billing', 'general', 'feature_request', 'bug_report') DEFAULT 'general'");
    }
};
