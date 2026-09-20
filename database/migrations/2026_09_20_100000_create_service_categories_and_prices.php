<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service_categories')) {
            Schema::create('service_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('services', 'service_category_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->foreignId('service_category_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('services', 'fulfillment_type')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('fulfillment_type')->default('manual')->after('service_type');
            });
        }

        if (!Schema::hasTable('service_prices')) {
            Schema::create('service_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_id')->constrained()->cascadeOnDelete();
                $table->string('billing_cycle');
                $table->decimal('price', 10, 2);
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();
                $table->unique(['service_id', 'billing_cycle']);
            });
        }

        $categoryIds = [];
        foreach (DB::table('services')->select('service_type')->distinct()->pluck('service_type') as $index => $type) {
            $name = match ($type) {
                'website_pakket' => 'Websites',
                'hosting' => 'Hosting',
                default => 'Overige diensten',
            };
            if (!isset($categoryIds[$name])) {
                $categoryIds[$name] = DB::table('service_categories')->where('slug', Str::slug($name))->value('id')
                    ?? DB::table('service_categories')->insertGetId([
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'sort_order' => $index,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }
            $categoryId = $categoryIds[$name];
            DB::table('services')->where('service_type', $type)->update([
                'service_category_id' => $categoryId,
                'fulfillment_type' => $type === 'hosting' ? 'directadmin' : 'manual',
            ]);
        }

        foreach (DB::table('services')->whereNotNull('price')->get() as $service) {
            $cycle = match ($service->price_type) {
                'maandelijks' => 'monthly',
                'jaarlijks' => 'yearly',
                default => 'one_time',
            };
            DB::table('service_prices')->updateOrInsert(
                ['service_id' => $service->id, 'billing_cycle' => $cycle],
                [
                    'price' => $service->price,
                    'is_enabled' => $service->price_type !== 'op-aanvraag',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_prices');
        Schema::table('services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_category_id');
            $table->dropColumn('fulfillment_type');
        });
        Schema::dropIfExists('service_categories');
    }
};
