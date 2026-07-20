<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('street')->nullable()->after('company');
            $table->string('house_number')->nullable()->after('street');
            $table->string('postal_code')->nullable()->after('house_number');
            $table->string('city')->nullable()->after('postal_code');
            $table->string('country')->nullable()->default('Nederland')->after('city');
            $table->string('kvk_number')->nullable()->after('country');
            $table->string('vat_number')->nullable()->after('kvk_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['street', 'house_number', 'postal_code', 'city', 'country', 'kvk_number', 'vat_number']);
        });
    }
};
