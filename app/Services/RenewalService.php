<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RenewalService
{
    public function __construct(
        private InvoiceService $invoices,
        private MolliePaymentService $payments,
        private SubscriptionPeriodService $periods
    ) {
    }

    public function run(): array
    {
        $result = ['created' => 0, 'automatic' => 0, 'payment_links' => 0, 'failed' => 0];

        CustomerService::with(['user', 'service'])
            ->where('status', 'active')
            ->where('auto_renew', true)
            ->where('cancel_at_period_end', false)
            ->whereNotIn('billing_cycle', ['one_time'])
            ->whereDate('next_invoice_date', '<=', today())
            ->orderBy('id')
            ->chunkById(100, function ($services) use (&$result) {
                foreach ($services as $customerService) {
                    try {
                        $invoice = $this->processOne($customerService);
                        if (!$invoice) {
                            continue;
                        }
                        $result['created']++;
                        $invoice->payment_url ? $result['payment_links']++ : $result['automatic']++;
                    } catch (\Throwable $exception) {
                        report($exception);
                        $result['failed']++;
                    }
                }
            });

        return $result;
    }

    public function processOne(CustomerService $customerService): ?Invoice
    {
        $customerService->loadMissing(['user', 'service']);
        if ($customerService->status !== 'active'
            || !$customerService->auto_renew
            || $customerService->cancel_at_period_end
            || $customerService->billing_cycle === 'one_time'
            || !$customerService->next_invoice_date
            || $customerService->next_invoice_date->isAfter(today())) {
            return null;
        }

        $invoice = $this->createRenewalInvoice($customerService);
        if (!$invoice) {
            return null;
        }

        if ($customerService->payment_method === 'auto_debit' && $customerService->user->mollie_customer_id) {
            try {
                $this->payments->createRecurringPayment($invoice);
            } catch (\Throwable $exception) {
                report($exception);
                $this->payments->createPayment($invoice);
            }
        } else {
            $this->payments->createPayment($invoice);
        }

        $this->notifyCustomer($invoice);

        return $invoice->fresh();
    }

    private function createRenewalInvoice(CustomerService $customerService): ?Invoice
    {
        return DB::transaction(function () use ($customerService) {
            $locked = CustomerService::with(['user', 'service'])->lockForUpdate()->findOrFail($customerService->id);
            $periodStart = $locked->current_period_end?->copy()->addDay() ?? today();
            $periodEnd = $this->periods->endFor($locked->billing_cycle, $periodStart);
            if (!$periodEnd) {
                return null;
            }

            $key = "renewal:{$locked->id}:{$periodStart->format('Y-m-d')}";
            $existing = Invoice::where('renewal_key', $key)->first();
            if ($existing) {
                return !$existing->mollie_payment_id && $existing->status === 'concept'
                    ? $existing->load(['user', 'customerService.service'])
                    : null;
            }

            $invoice = $this->invoices->createFromCustomerService($locked);
            $invoice->update([
                'renewal_key' => $key,
                'customer_service_id' => $locked->id,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
            ]);

            return $invoice->fresh(['user', 'customerService.service']);
        });
    }

    private function notifyCustomer(Invoice $invoice): void
    {
        Notification::notify(
            $invoice->user,
            'invoice',
            'Nieuwe verlengingsfactuur',
            "Factuur {$invoice->invoice_number} voor de verlenging van uw dienst staat klaar.",
            route('customer.invoices.show', $invoice)
        );

        try {
            Mail::send('renewal-email', ['invoice' => $invoice], function ($message) use ($invoice) {
                $message->to($invoice->user->email, $invoice->user->name)
                    ->subject("Verlengingsfactuur {$invoice->invoice_number}");
            });
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
