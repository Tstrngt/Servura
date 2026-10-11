<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->string('provider_environment', 20)->nullable()->after('provider')->index();
        });
    }

    public function down(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->dropIndex(['provider_environment']);
            $table->dropColumn('provider_environment');
        });
    }
};
