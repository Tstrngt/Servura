<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->string('type')->default('registration')->after('id');
            $table->text('auth_code')->nullable()->after('error_message');
            $table->decimal('transfer_price', 10, 2)->nullable()->after('renewal_price');
        });
    }

    public function down(): void
    {
        Schema::table('domain_registrations', function (Blueprint $table) {
            $table->dropColumn(['type', 'auth_code', 'transfer_price']);
        });
    }
};
