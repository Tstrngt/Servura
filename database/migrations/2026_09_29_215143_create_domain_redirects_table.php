<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_redirects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source_path')->default('/');
            $table->string('target_url');
            $table->string('type')->default('301')->comment('301 or 302');
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->index(['domain_registration_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_redirects');
    }
};
