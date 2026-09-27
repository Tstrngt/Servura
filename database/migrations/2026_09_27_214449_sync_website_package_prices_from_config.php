<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $packages = config('pricing.packages', []);

        foreach ($packages as $slug => $config) {
            if (! isset($config['basePrice'])) {
                continue;
            }

            DB::table('services')
                ->where('slug', $slug)
                ->where('service_type', 'website_pakket')
                ->update([
                    'price' => $config['basePrice'],
                    'price_type' => 'eenmalig',
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Geen rollback nodig; prijzen kunnen opnieuw uit de configuratie worden gesynchroniseerd.
    }
};
