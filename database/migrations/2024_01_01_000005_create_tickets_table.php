<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $text('description');
            $enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $enum('status', ['open', 'in_progress', 'waiting_for_customer', 'resolved', 'closed'])->default('open');
            $enum('category', ['technical', 'billing', 'general', 'feature_request', 'bug_report'])->default('general');
            $timestamp('last_reply_at')->nullable();
            $timestamp('resolved_at')->nullable();
            $timestamp('closed_at')->nullable();
            $text('resolution_notes')->nullable();
            $timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['status', 'priority']);
            $table->index('assigned_to');
            $table->index('category');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
