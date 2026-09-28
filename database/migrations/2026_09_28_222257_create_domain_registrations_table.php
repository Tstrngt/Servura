<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('domain_name');
            $table->string('tld');
            $table->string('status')->default('pending');
            $table->string('provider')->default('transip');
            $table->decimal('registration_price', 10, 2)->nullable();
            $table->decimal('renewal_price', 10, 2)->nullable();
            $table->date('registered_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->string('external_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['domain_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_registrations');
    }
};
