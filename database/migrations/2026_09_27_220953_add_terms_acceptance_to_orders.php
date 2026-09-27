<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('terms_accepted_at')->nullable()->after('status');
            $table->string('terms_version')->nullable()->after('terms_accepted_at');
            $table->string('hosting_terms_version')->nullable()->after('terms_version');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['terms_accepted_at', 'terms_version', 'hosting_terms_version']);
        });
    }
};
