<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Quote;
use App\Models\QuoteLine;
use App\Models\Service;
use App\Models\TransactionLog;
use App\Models\User;

class QuoteService
{
    /**
     * Create a quote for a customer with product lines and/or custom lines.
     */
    public function create(User $customer, array $lines, ?string $notes = null, ?int $validDays = 30, ?int $performedBy = null): Quote
    {
        $quote = Quote::create([
            'quote_number' => Quote::generateNumber(),
            'user_id' => $customer->id,
            'quote_date' => now(),
            'valid_until' => now()->addDays($validDays),
            'vat_percentage' => 21.00,
            'status' => 'concept',
            'notes' => $notes,
        ]);

        foreach ($lines as $i => $line) {
            $total = ($line['quantity'] ?? 1) * $line['unit_price'];
            QuoteLine::create([
                'quote_id' => $quote->id,
                'description' => $line['description'],
                'quantity' => $line['quantity'] ?? 1,
                'unit_price' => $line['unit_price'],
                'total' => $total,
                'service_id' => $line['service_id'] ?? null,
                'sort_order' => $i,
            ]);
        }

        $quote->recalculate();

        // Create suspended customer services for product lines
        foreach ($quote->lines()->whereNotNull('service_id')->get() as $line) {
            CustomerService::create([
                'user_id' => $customer->id,
                'service_id' => $line->service_id,
                'status' => 'suspended',
                'price' => $line->unit_price,
                'price_type' => Service::find($line->service_id)->price_type ?? 'eenmalig',
                'start_date' => now(),
            ]);
        }

        TransactionLog::create([
            'user_id' => $customer->id,
            'loggable_type' => Quote::class,
            'loggable_id' => $quote->id,
            'action' => 'aangemaakt',
            'description' => "Offerte {$quote->quote_number} aangemaakt",
            'performed_by' => $performedBy,
        ]);

        return $quote;
    }

    /**
     * Mark quote as sent.
     */
    public function markSent(Quote $quote, ?int $performedBy = null): void
    {
        $quote->update([
            'status' => 'verzonden',
            'sent_at' => now(),
        ]);

        TransactionLog::create([
            'user_id' => $quote->user_id,
            'loggable_type' => Quote::class,
            'loggable_id' => $quote->id,
            'action' => 'verzonden',
            'description' => "Offerte {$quote->quote_number} verzonden naar klant",
            'performed_by' => $performedBy,
        ]);
    }

    /**
     * Accept quote: convert to invoice.
     */
    public function accept(Quote $quote): Invoice
    {
        // Create invoice from quote
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'user_id' => $quote->user_id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'vat_percentage' => $quote->vat_percentage,
            'status' => 'verzonden',
            'notes' => $quote->notes,
        ]);

        // Copy lines and link customer_service_id where applicable
        foreach ($quote->lines as $quoteLine) {
            $customerServiceId = null;
            if ($quoteLine->service_id) {
                $cs = CustomerService::where('user_id', $quote->user_id)
                    ->where('service_id', $quoteLine->service_id)
                    ->where('status', 'suspended')
                    ->first();
                $customerServiceId = $cs?->id;
            }

            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'description' => $quoteLine->description,
                'quantity' => $quoteLine->quantity,
                'unit_price' => $quoteLine->unit_price,
                'total' => $quoteLine->total,
                'customer_service_id' => $customerServiceId,
                'sort_order' => $quoteLine->sort_order,
            ]);
        }

        $invoice->recalculate();

        // Update quote
        $quote->update([
            'status' => 'geaccepteerd',
            'accepted_at' => now(),
            'converted_invoice_id' => $invoice->id,
        ]);

        TransactionLog::create([
            'user_id' => $quote->user_id,
            'loggable_type' => Quote::class,
            'loggable_id' => $quote->id,
            'action' => 'geaccepteerd',
            'description' => "Offerte {$quote->quote_number} geaccepteerd, factuur {$invoice->invoice_number} aangemaakt",
        ]);

        return $invoice;
    }

    /**
     * Reject quote.
     */
    public function reject(Quote $quote): void
    {
        $quote->update(['status' => 'afgewezen']);

        // Remove suspended services linked to this quote
        foreach ($quote->lines()->whereNotNull('service_id')->get() as $line) {
            CustomerService::where('user_id', $quote->user_id)
                ->where('service_id', $line->service_id)
                ->where('status', 'suspended')
                ->delete();
        }

        TransactionLog::create([
            'user_id' => $quote->user_id,
            'loggable_type' => Quote::class,
            'loggable_id' => $quote->id,
            'action' => 'afgewezen',
            'description' => "Offerte {$quote->quote_number} afgewezen door klant",
        ]);
    }
}
