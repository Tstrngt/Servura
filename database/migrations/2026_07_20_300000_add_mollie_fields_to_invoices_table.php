<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('mollie_payment_id')->nullable()->after('paid_at');
            $table->string('payment_url')->nullable()->after('mollie_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['mollie_payment_id', 'payment_url']);
        });
    }
};
