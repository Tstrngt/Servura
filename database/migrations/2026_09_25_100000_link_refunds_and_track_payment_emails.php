<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('parent_transaction_id')->nullable()->after('invoice_id')->constrained('transactions')->nullOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->timestamp('payment_confirmation_sent_at')->nullable()->after('paid_at');
        });

        DB::table('transactions')
            ->where('type', 'creditering')
            ->whereNull('parent_transaction_id')
            ->orderBy('id')
            ->each(function ($refund) {
                $paymentId = DB::table('transactions')
                    ->where('invoice_id', $refund->invoice_id)
                    ->where('type', 'inkomst')
                    ->where('status', 'voltooid')
                    ->orderByDesc('transaction_date')
                    ->orderByDesc('id')
                    ->value('id');

                if ($paymentId) {
                    DB::table('transactions')->where('id', $refund->id)->update(['parent_transaction_id' => $paymentId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_transaction_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_confirmation_sent_at');
        });
    }
};
