<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_prices', function (Blueprint $table) {
            $table->string('tld')->nullable()->after('billing_cycle');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropUnique(['service_id', 'billing_cycle']);
            $table->unique(['service_id', 'billing_cycle', 'tld'], 'service_prices_service_cycle_tld_unique');
        });
    }

    public function down(): void
    {
        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropUnique('service_prices_service_cycle_tld_unique');
            $table->unique(['service_id', 'billing_cycle']);
            $table->dropColumn('tld');
        });
    }
};
