<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('batch_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('mollie_payment_id')->nullable()->unique();
            $table->text('checkout_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        Schema::create('payment_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['payment_batch_id', 'invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_batch_items');
        Schema::dropIfExists('payment_batches');
    }
};
