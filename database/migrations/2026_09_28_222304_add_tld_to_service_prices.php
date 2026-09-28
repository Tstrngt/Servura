<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('service_prices', 'tld')) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->string('tld')->nullable()->after('billing_cycle');
            });
        }

        $indexes = $this->indexesByName('service_prices');
        $oldIndexExists = isset($indexes['service_prices_service_id_billing_cycle_unique']);
        $newIndexExists = isset($indexes['service_prices_service_cycle_tld_unique']);

        if ($oldIndexExists || ! $newIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->index('service_id', 'service_prices_service_id_temp_index');
            });
        }

        if ($oldIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropUnique(['service_id', 'billing_cycle']);
            });
        }

        if (! $newIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->unique(['service_id', 'billing_cycle', 'tld'], 'service_prices_service_cycle_tld_unique');
            });
        }

        if ($oldIndexExists || ! $newIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropIndex('service_prices_service_id_temp_index');
            });
        }
    }

    public function down(): void
    {
        $indexes = $this->indexesByName('service_prices');
        $newIndexExists = isset($indexes['service_prices_service_cycle_tld_unique']);
        $oldIndexExists = isset($indexes['service_prices_service_id_billing_cycle_unique']);

        if ($newIndexExists || ! $oldIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->index('service_id', 'service_prices_service_id_temp_index');
            });
        }

        if ($newIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropUnique('service_prices_service_cycle_tld_unique');
            });
        }

        if (! $oldIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->unique(['service_id', 'billing_cycle']);
            });
        }

        if ($newIndexExists || ! $oldIndexExists) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropIndex('service_prices_service_id_temp_index');
            });
        }

        if (Schema::hasColumn('service_prices', 'tld')) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropColumn('tld');
            });
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function indexesByName(string $table): array
    {
        return collect(Schema::getIndexes($table))
            ->mapWithKeys(fn (array $index) => [strtolower($index['name']) => $index])
            ->all();
    }
};
