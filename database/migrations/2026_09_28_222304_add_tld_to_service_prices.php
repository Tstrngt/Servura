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

        // MySQL requires an index on service_id because a foreign key uses it.
        // Add a temporary index, drop the old unique, add the new unique, then remove the temporary index.
        Schema::table('service_prices', function (Blueprint $table) {
            $table->index('service_id', 'service_prices_service_id_temp_index');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropUnique(['service_id', 'billing_cycle']);
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->unique(['service_id', 'billing_cycle', 'tld'], 'service_prices_service_cycle_tld_unique');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropIndex('service_prices_service_id_temp_index');
        });
    }

    public function down(): void
    {
        Schema::table('service_prices', function (Blueprint $table) {
            $table->index('service_id', 'service_prices_service_id_temp_index');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropUnique('service_prices_service_cycle_tld_unique');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->unique(['service_id', 'billing_cycle']);
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropIndex('service_prices_service_id_temp_index');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropColumn('tld');
        });
    }
};
