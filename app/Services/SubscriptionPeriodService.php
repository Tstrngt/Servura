<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\CustomerService;
use Carbon\CarbonInterface;

class SubscriptionPeriodService
{
    public function endFor(string $cycle, CarbonInterface $start): ?CarbonInterface
    {
        $end = match ($cycle) {
            'monthly' => $start->copy()->addMonthNoOverflow(),
            'quarterly' => $start->copy()->addMonthsNoOverflow(3),
            'semiannual' => $start->copy()->addMonthsNoOverflow(6),
            'yearly' => $start->copy()->addYearNoOverflow(),
            'biennial' => $start->copy()->addYearsNoOverflow(2),
            'triennial' => $start->copy()->addYearsNoOverflow(3),
            default => null,
        };

        return $end?->subDay();
    }

    public function activateInitialPeriod(CustomerService $customerService): void
    {
        $start = now()->startOfDay();
        $end = $this->endFor($customerService->billing_cycle ?? $customerService->price_type, $start);
        $customerService->update([
            'status' => 'active',
            'start_date' => $start,
            'current_period_start' => $start,
            'current_period_end' => $end,
            'end_date' => $end,
            'next_invoice_date' => $end?->copy()->subDays(BillingSetting::integer('invoice_due_days', 14)),
        ]);
    }

    public function applyRenewalPeriod(CustomerService $customerService, CarbonInterface $start, CarbonInterface $end): void
    {
        $customerService->update([
            'status' => 'active',
            'current_period_start' => $start,
            'current_period_end' => $end,
            'end_date' => $end,
            'next_invoice_date' => $end->copy()->subDays(BillingSetting::integer('invoice_due_days', 14)),
        ]);
    }
}
