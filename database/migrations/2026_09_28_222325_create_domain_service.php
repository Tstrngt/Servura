<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('service_categories')->insertGetId([
            'name' => 'Domeinen',
            'slug' => Str::slug('Domeinen'),
            'description' => 'Domeinregistratie en -beheer',
            'sort_order' => 99,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('services')->insert([
            'service_category_id' => $categoryId,
            'title' => 'Domeinregistratie',
            'slug' => 'domeinregistratie',
            'service_type' => 'domain',
            'fulfillment_type' => 'domain',
            'short_description' => 'Registreer en beheer je domeinnaam.',
            'description' => 'Domeinregistratie via TransIP.',
            'price' => null,
            'price_type' => 'jaarlijks',
            'features' => json_encode([]),
            'is_popular' => false,
            'sort_order' => 99,
            'is_active' => true,
            'show_on_homepage' => false,
            'show_on_services_page' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $serviceId = DB::table('services')->where('slug', 'domeinregistratie')->value('id');
        if ($serviceId) {
            DB::table('service_prices')->where('service_id', $serviceId)->delete();
            DB::table('services')->where('id', $serviceId)->delete();
        }

        $categoryId = DB::table('service_categories')->where('slug', 'domeinen')->value('id');
        if ($categoryId) {
            DB::table('service_categories')->where('id', $categoryId)->delete();
        }
    }
};
