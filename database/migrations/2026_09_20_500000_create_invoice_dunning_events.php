<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_services', function (Blueprint $table) {
            $table->string('suspension_reason')->nullable()->after('status');
            $table->timestamp('suspended_at')->nullable()->after('suspension_reason');
        });

        Schema::create('invoice_dunning_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('event_type');
            $table->timestamp('processed_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['invoice_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_dunning_events');
        Schema::table('customer_services', function (Blueprint $table) {
            $table->dropColumn(['suspension_reason', 'suspended_at']);
        });
    }
};
