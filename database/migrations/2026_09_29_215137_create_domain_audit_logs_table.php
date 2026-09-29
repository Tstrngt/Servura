<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->comment('Null for system-initiated actions');
            $table->string('action');
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->text('note')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('status')->default('completed')->comment('queued/processing/completed/failed');
            $table->timestamps();

            $table->index(['domain_registration_id', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_audit_logs');
    }
};
