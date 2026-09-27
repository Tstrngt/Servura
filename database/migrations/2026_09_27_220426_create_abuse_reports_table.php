<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abuse_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('domain')->nullable();
            $table->string('url');
            $table->string('category');
            $table->text('description');
            $table->text('reason');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abuse_reports');
    }
};
