<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_internal_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_registration_id')->constrained('domain_registrations')->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->string('status')->default('pending'); // pending, accepted, rejected, cancelled
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_internal_transfers');
    }
};
