<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PaymentBatch;
use App\Models\PaymentBatchItem;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Mollie\Laravel\Facades\Mollie;

class MolliePaymentService
{
    public function __construct(
        private SubscriptionPeriodService $periods,
        private ProvisioningService $provisioning
    ) {}

    /**
     * Create a Mollie payment for an invoice.
     */
    public function createPayment(Invoice $invoice, bool $establishMandate = false): string
    {
        $payload = [
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format($invoice->total, 2, '.', ''),
            ],
            'description' => "Factuur {$invoice->invoice_number}",
            'redirectUrl' => route('customer.invoices.payment.return', $invoice),
            'webhookUrl' => route('mollie.webhook'),
            'metadata' => [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ],
        ];

        if ($establishMandate) {
            $payload['customerId'] = $this->ensureCustomer($invoice->user);
            $payload['sequenceType'] = 'first';
        }

        $payment = Mollie::api()->payments->create($payload);

        $invoice->update([
            'mollie_payment_id' => $payment->id,
            'payment_url' => $payment->getCheckoutUrl(),
            'status' => 'verzonden',
        ]);

        TransactionLog::create([
            'user_id' => $invoice->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'betaling_gestart',
            'description' => "Mollie betaling gestart voor factuur {$invoice->invoice_number}",
            'metadata' => ['mollie_payment_id' => $payment->id],
            'performed_by' => $invoice->user_id,
        ]);

        return $payment->getCheckoutUrl();
    }

    public function createBatchPayment(User $user, array $invoiceIds): PaymentBatch
    {
        $reusable = $this->resolveOpenBatches($user, $invoiceIds);
        if ($reusable) {
            return $reusable;
        }

        $batch = DB::transaction(function () use ($user, $invoiceIds) {
            $invoices = Invoice::query()
                ->where('user_id', $user->id)
                ->whereIn('id', array_unique($invoiceIds))
                ->whereIn('status', ['verzonden', 'openstaand', 'vervallen'])
                ->lockForUpdate()
                ->get();

            if ($invoices->count() !== count(array_unique($invoiceIds)) || $invoices->isEmpty()) {
                throw new \InvalidArgumentException('Een of meer facturen zijn niet beschikbaar voor betaling.');
            }

            $batch = PaymentBatch::create([
                'user_id' => $user->id,
                'batch_number' => PaymentBatch::generateNumber(),
                'amount' => $invoices->sum(fn (Invoice $invoice) => (float) $invoice->total),
                'status' => 'pending',
            ]);

            $invoices->each(fn (Invoice $invoice) => $batch->items()->create([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total,
                'status' => 'pending',
            ]));

            return $batch->load('items.invoice');
        });

        try {
            $payment = Mollie::api()->payments->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => number_format($batch->amount, 2, '.', ''),
                ],
                'description' => "Betaling {$batch->batch_number}",
                'redirectUrl' => route('customer.financial.payment.return', $batch),
                'webhookUrl' => route('mollie.webhook'),
                'metadata' => [
                    'payment_batch_id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                ],
            ]);

            $batch->update([
                'mollie_payment_id' => $payment->id,
                'checkout_url' => $payment->getCheckoutUrl(),
                'status' => 'processing',
            ]);
        } catch (\Throwable $exception) {
            $batch->update(['status' => 'failed']);
            throw $exception;
        }

        return $batch->fresh('items.invoice');
    }

    public function createRecurringPayment(Invoice $invoice): void
    {
        if (! $invoice->user->mollie_customer_id) {
            throw new \RuntimeException('Geen Mollie-klant beschikbaar voor automatische incasso.');
        }

        $payment = Mollie::api()->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format($invoice->total, 2, '.', ''),
            ],
            'customerId' => $invoice->user->mollie_customer_id,
            'sequenceType' => 'recurring',
            'description' => "Verlenging {$invoice->invoice_number}",
            'webhookUrl' => route('mollie.webhook'),
            'metadata' => [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ],
        ]);

        $invoice->update([
            'mollie_payment_id' => $payment->id,
            'payment_url' => null,
            'status' => 'in_behandeling',
        ]);
    }

    private function ensureCustomer(User $user): string
    {
        if ($user->mollie_customer_id) {
            return $user->mollie_customer_id;
        }

        $customer = Mollie::api()->customers->create([
            'name' => $user->name,
            'email' => $user->email,
            'metadata' => ['user_id' => $user->id],
        ]);
        $user->update(['mollie_customer_id' => $customer->id]);

        return $customer->id;
    }

    public function finalizePaidInvoice(Invoice $invoice): void
    {
        $customerService = $invoice->customerService
            ?? CustomerService::find($invoice->lines()->whereNotNull('customer_service_id')->value('customer_service_id'));
        if ($customerService) {
            $isRenewal = $invoice->period_start && $invoice->period_end;
            $wasExternallySuspended = $customerService->provisioning_status === 'suspended'
                || ($customerService->external_username && $customerService->external_suspended_at);
            $needsDomain = $customerService->service->fulfillment_type === 'directadmin'
                && ! $customerService->domain
                && ! $customerService->external_username;

            if ($needsDomain) {
                $customerService->update([
                    'status' => 'suspended',
                    'suspension_reason' => 'domain_required',
                    'suspended_at' => now(),
                ]);

                User::staff()->each(function (User $staff) use ($customerService) {
                    \App\Models\Notification::notify(
                        $staff,
                        'service',
                        'Domein nodig voor provisioning',
                        "Dienst {$customerService->service->title} van {$customerService->user->name} is betaald, maar er is geen domein bekend. Vul het domein in en start provisioning.",
                        route('admin.customers.show', [$customerService->user, 'tab' => 'services'])
                    );
                });

            } elseif ($isRenewal) {
                $this->periods->applyRenewalPeriod($customerService, $invoice->period_start, $invoice->period_end);
            } elseif (! $customerService->current_period_start) {
                $this->periods->activateInitialPeriod($customerService);
            } else {
                $customerService->update([
                    'status' => 'active',
                    'suspension_reason' => null,
                    'suspended_at' => null,
                ]);
            }

            $customerService->refresh()->loadMissing(['user', 'service.serverConnection']);
            if ($wasExternallySuspended) {
                $this->provisioning->unsuspend($customerService);
            } elseif (! $isRenewal
                && $customerService->service->fulfillment_type === 'directadmin'
                && in_array($customerService->provisioning_status, ['pending_payment', 'processing', 'failed'], true)
                && $customerService->domain
                && $customerService->service->serverConnection) {
                $this->provisioning->provision($customerService);
            } elseif ($isRenewal
                && $customerService->provisioning_status === 'failed'
                && ! $customerService->external_username) {
                $customerService->update([
                    'status' => 'active',
                    'suspension_reason' => null,
                    'suspended_at' => null,
                    'provisioning_status' => 'not_required',
                    'provisioning_error' => null,
                ]);
            }
        }

        Order::where('invoice_id', $invoice->id)
            ->where('status', 'pending_payment')
            ->update(['status' => 'paid', 'paid_at' => now()]);
    }

    /**
     * Handle Mollie webhook callback.
     */
    public function handleWebhook(string $paymentId): void
    {
        $payment = Mollie::api()->payments->get($paymentId);

        if (isset($payment->metadata->payment_batch_id)) {
            $this->handleBatchWebhook($payment);

            return;
        }

        $invoiceId = $payment->metadata->invoice_id;
        $invoice = Invoice::findOrFail($invoiceId);

        if ($payment->isPaid()) {
            if ($invoice->status === 'betaald') {
                $this->finalizePaidInvoice($invoice);

                return;
            }

            $invoice->update([
                'status' => 'betaald',
                'paid_at' => now(),
            ]);

            Transaction::create([
                'transaction_number' => Transaction::generateNumber(),
                'user_id' => $invoice->user_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total,
                'type' => 'inkomst',
                'payment_method' => 'ideal',
                'status' => 'voltooid',
                'description' => "Betaling factuur {$invoice->invoice_number}",
                'transaction_date' => now(),
                'reference' => $paymentId,
            ]);

            $this->finalizePaidInvoice($invoice);

            TransactionLog::create([
                'user_id' => $invoice->user_id,
                'loggable_type' => Invoice::class,
                'loggable_id' => $invoice->id,
                'action' => 'betaald',
                'description' => "Factuur {$invoice->invoice_number} betaald via Mollie",
                'metadata' => ['mollie_payment_id' => $paymentId],
            ]);
        } elseif ($payment->isFailed() || $payment->isExpired() || $payment->isCanceled()) {
            // Reset to verzonden so customer can try again
            if ($invoice->status !== 'betaald') {
                $invoice->update(['status' => 'verzonden']);
            }

            TransactionLog::create([
                'user_id' => $invoice->user_id,
                'loggable_type' => Invoice::class,
                'loggable_id' => $invoice->id,
                'action' => 'betaling_mislukt',
                'description' => "Betaling voor factuur {$invoice->invoice_number} mislukt/geannuleerd",
                'metadata' => ['mollie_payment_id' => $paymentId, 'status' => $payment->status],
            ]);
        }
    }

    /**
     * Resolve stale/open payment batches for the given invoices.
     * Returns a reusable open batch, or null when a new batch may be created.
     */
    private function resolveOpenBatches(User $user, array $invoiceIds): ?PaymentBatch
    {
        $batches = PaymentBatch::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->whereHas('items', fn ($query) => $query->whereIn('invoice_id', array_unique($invoiceIds)))
            ->oldest()
            ->get();

        foreach ($batches as $openBatch) {
            if (! $openBatch->mollie_payment_id) {
                // Batch without a Mollie payment is stale once creation has long passed.
                if ($openBatch->created_at->lt(now()->subMinutes(15))) {
                    $openBatch->update(['status' => 'failed']);
                    $openBatch->items()->where('status', 'pending')->update(['status' => 'failed']);
                }
                continue;
            }

            $payment = Mollie::api()->payments->get($openBatch->mollie_payment_id);

            if ($payment->isPaid()) {
                $this->handleBatchWebhook($payment);
                continue;
            }

            if ($payment->isFailed() || $payment->isExpired() || $payment->isCanceled()) {
                $openBatch->update(['status' => 'failed']);
                $openBatch->items()->where('status', 'pending')->update(['status' => 'failed']);
                continue;
            }

            // Payment still open at Mollie: reuse the existing checkout link.
            if ($openBatch->checkout_url) {
                return $openBatch;
            }
        }

        $blockedInvoiceIds = PaymentBatchItem::query()
            ->whereIn('invoice_id', array_unique($invoiceIds))
            ->whereHas('batch', fn ($query) => $query
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing']))
            ->pluck('invoice_id');

        if ($blockedInvoiceIds->isNotEmpty()) {
            throw new \InvalidArgumentException('Een van deze facturen zit al in een lopende betaling.');
        }

        return null;
    }

    private function handleBatchWebhook(object $payment): void
    {
        $batch = PaymentBatch::with('items.invoice')->findOrFail($payment->metadata->payment_batch_id);

        if ($batch->mollie_payment_id !== $payment->id) {
            throw new \RuntimeException('Mollie betaling hoort niet bij deze betaalbatch.');
        }

        if ($payment->isPaid()) {
            DB::transaction(function () use ($batch, $payment) {
                $lockedBatch = PaymentBatch::query()->lockForUpdate()->findOrFail($batch->id);

                if ($lockedBatch->status === 'paid') {
                    return;
                }

                $lockedBatch->load('items.invoice');
                $lockedBatch->items->each(function (PaymentBatchItem $item) use ($payment) {
                    $invoice = $item->invoice;

                    if ($invoice->status !== 'betaald') {
                        $invoice->update(['status' => 'betaald', 'paid_at' => now()]);
                        Transaction::create([
                            'transaction_number' => Transaction::generateNumber(),
                            'user_id' => $invoice->user_id,
                            'invoice_id' => $invoice->id,
                            'amount' => $item->amount,
                            'type' => 'inkomst',
                            'payment_method' => 'ideal',
                            'status' => 'voltooid',
                            'description' => "Batchbetaling factuur {$invoice->invoice_number}",
                            'transaction_date' => now(),
                            'reference' => $payment->id.':'.$invoice->id,
                        ]);
                        $this->finalizePaidInvoice($invoice);
                    }

                    $item->update(['status' => 'paid', 'paid_at' => now()]);
                });

                $lockedBatch->update(['status' => 'paid', 'paid_at' => now()]);
            });

            return;
        }

        if ($payment->isFailed() || $payment->isExpired() || $payment->isCanceled()) {
            $batch->update(['status' => 'failed']);
            $batch->items()->where('status', 'pending')->update(['status' => 'failed']);
        }
    }
}
