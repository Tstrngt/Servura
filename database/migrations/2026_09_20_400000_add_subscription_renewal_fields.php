<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mollie_customer_id')->nullable()->after('vat_number');
        });

        Schema::table('customer_services', function (Blueprint $table) {
            $table->foreignId('service_price_id')->nullable()->after('service_id')->constrained()->nullOnDelete();
            $table->string('billing_cycle')->nullable()->after('price_type');
            $table->date('current_period_start')->nullable()->after('start_date');
            $table->date('current_period_end')->nullable()->after('current_period_start');
            $table->date('next_invoice_date')->nullable()->after('current_period_end');
            $table->boolean('auto_renew')->default(true)->after('next_invoice_date');
            $table->string('payment_method')->default('payment_link')->after('auto_renew');
            $table->boolean('cancel_at_period_end')->default(false)->after('payment_method');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_at_period_end');
            $table->index(['status', 'auto_renew', 'next_invoice_date'], 'customer_services_renewal_index');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('renewal_key')->nullable()->unique()->after('quote_id');
            $table->foreignId('customer_service_id')->nullable()->after('renewal_key')->constrained()->nullOnDelete();
            $table->date('period_start')->nullable()->after('customer_service_id');
            $table->date('period_end')->nullable()->after('period_start');
        });

        foreach (DB::table('customer_services')->get() as $customerService) {
            $cycle = match ($customerService->price_type) {
                'maandelijks' => 'monthly',
                'jaarlijks' => 'yearly',
                'eenmalig', 'op-aanvraag' => 'one_time',
                default => $customerService->price_type,
            };
            $start = Carbon::parse($customerService->start_date);
            $periodStart = !$customerService->end_date && $cycle !== 'one_time' ? today() : $start;
            $end = $customerService->end_date ? Carbon::parse($customerService->end_date) : match ($cycle) {
                'monthly' => $periodStart->copy()->addMonthNoOverflow()->subDay(),
                'quarterly' => $periodStart->copy()->addMonthsNoOverflow(3)->subDay(),
                'semiannual' => $periodStart->copy()->addMonthsNoOverflow(6)->subDay(),
                'yearly' => $periodStart->copy()->addYearNoOverflow()->subDay(),
                'biennial' => $periodStart->copy()->addYearsNoOverflow(2)->subDay(),
                'triennial' => $periodStart->copy()->addYearsNoOverflow(3)->subDay(),
                default => null,
            };
            $servicePriceId = DB::table('service_prices')
                ->where('service_id', $customerService->service_id)
                ->where('billing_cycle', $cycle)
                ->value('id');

            DB::table('customer_services')->where('id', $customerService->id)->update([
                'service_price_id' => $servicePriceId,
                'billing_cycle' => $cycle,
                'current_period_start' => $periodStart->toDateString(),
                'current_period_end' => $end?->toDateString(),
                'end_date' => $end?->toDateString(),
                'next_invoice_date' => $cycle !== 'one_time' ? $end?->copy()->subDays(14)->toDateString() : null,
                'auto_renew' => $cycle !== 'one_time',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_service_id');
            $table->dropUnique(['renewal_key']);
            $table->dropColumn(['renewal_key', 'period_start', 'period_end']);
        });
        Schema::table('customer_services', function (Blueprint $table) {
            $table->dropIndex('customer_services_renewal_index');
            $table->dropConstrainedForeignId('service_price_id');
            $table->dropColumn([
                'billing_cycle', 'current_period_start', 'current_period_end', 'next_invoice_date',
                'auto_renew', 'payment_method', 'cancel_at_period_end', 'cancelled_at',
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mollie_customer_id');
        });
    }
};
