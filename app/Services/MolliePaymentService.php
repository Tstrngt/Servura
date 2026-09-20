<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\User;
use Mollie\Laravel\Facades\Mollie;

class MolliePaymentService
{
    public function __construct(private SubscriptionPeriodService $periods)
    {
    }

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

    public function createRecurringPayment(Invoice $invoice): void
    {
        if (!$invoice->user->mollie_customer_id) {
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
            if ($invoice->period_start && $invoice->period_end) {
                $this->periods->applyRenewalPeriod($customerService, $invoice->period_start, $invoice->period_end);
            } elseif (!$customerService->current_period_start) {
                $this->periods->activateInitialPeriod($customerService);
            } else {
                $customerService->update([
                    'status' => 'active',
                    'suspension_reason' => null,
                    'suspended_at' => null,
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
}
