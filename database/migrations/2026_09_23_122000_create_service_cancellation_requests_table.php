<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->string('policy_type');
            $table->date('requested_at');
            $table->date('effective_at');
            $table->date('original_end_date')->nullable();
            $table->decimal('estimated_usage_cost', 10, 2)->default(0);
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->index(['customer_service_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_cancellation_requests');
    }
};
