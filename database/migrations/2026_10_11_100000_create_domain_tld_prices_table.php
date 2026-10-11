<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_tld_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_tld_id')->constrained()->cascadeOnDelete();
            $table->string('environment', 20);
            $table->string('operation', 20);
            $table->unsignedSmallInteger('period')->default(1);
            $table->string('currency', 3)->default('EUR');
            $table->decimal('regular_cost', 12, 4)->nullable();
            $table->decimal('effective_cost', 12, 4)->nullable();
            $table->decimal('calculated_sale_price', 12, 2)->nullable();
            $table->decimal('override_sale_price', 12, 2)->nullable();
            $table->decimal('markup_percentage', 6, 2)->default(10);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_promotion')->default(false);
            $table->timestamp('promotion_starts_at')->nullable();
            $table->timestamp('promotion_ends_at')->nullable();
            $table->json('promotion_data')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->text('sync_error')->nullable();
            $table->timestamps();

            $table->unique(['domain_tld_id', 'environment', 'operation', 'period'], 'domain_tld_price_unique');
            $table->index(['environment', 'is_promotion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_tld_prices');
    }
};
