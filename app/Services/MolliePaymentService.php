<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Mollie\Laravel\Facades\Mollie;

class MolliePaymentService
{
    /**
     * Create a Mollie payment for an invoice.
     */
    public function createPayment(Invoice $invoice): string
    {
        $payment = Mollie::api()->payments->create([
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
        ]);

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

    /**
     * Handle Mollie webhook callback.
     */
    public function handleWebhook(string $paymentId): void
    {
        $payment = Mollie::api()->payments->get($paymentId);
        $invoiceId = $payment->metadata->invoice_id;
        $invoice = Invoice::findOrFail($invoiceId);

        if ($payment->isPaid()) {
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

            // Activate suspended customer services (start = payment date)
            $serviceIds = $invoice->lines()->whereNotNull('customer_service_id')->pluck('customer_service_id');
            if ($serviceIds->isNotEmpty()) {
                CustomerService::whereIn('id', $serviceIds)
                    ->where('status', 'suspended')
                    ->update(['status' => 'active', 'start_date' => now()]);
            }

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
