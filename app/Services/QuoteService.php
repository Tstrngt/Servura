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
    public function create(User $customer, array $data, array $lines, ?int $performedBy = null): Quote
    {
        $quote = Quote::create([
            'quote_number' => Quote::generateNumber(),
            'user_id' => $customer->id,
            'quote_date' => now(),
            'valid_until' => now()->addDays((int) ($data['valid_days'] ?? 30)),
            'vat_percentage' => 21.00,
            'status' => 'concept',
            'proposal' => $data['proposal'] ?? null,
            'notes' => $data['notes'] ?? null,
            'client_notes' => $data['client_notes'] ?? null,
            'internal_notes' => $data['internal_notes'] ?? null,
        ]);

        $this->saveLines($quote, $lines);

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
     * Update an existing quote.
     */
    public function update(Quote $quote, array $data, array $lines, ?int $performedBy = null): Quote
    {
        $quote->update([
            'valid_until' => isset($data['valid_days']) ? now()->addDays((int) $data['valid_days']) : $quote->valid_until,
            'proposal' => $data['proposal'] ?? $quote->proposal,
            'notes' => $data['notes'] ?? $quote->notes,
            'client_notes' => $data['client_notes'] ?? $quote->client_notes,
            'internal_notes' => $data['internal_notes'] ?? $quote->internal_notes,
        ]);

        // Delete old lines and re-create
        $quote->lines()->delete();
        $this->saveLines($quote, $lines);

        TransactionLog::create([
            'user_id' => $quote->user_id,
            'loggable_type' => Quote::class,
            'loggable_id' => $quote->id,
            'action' => 'bijgewerkt',
            'description' => "Offerte {$quote->quote_number} bijgewerkt",
            'performed_by' => $performedBy,
        ]);

        return $quote;
    }

    private function saveLines(Quote $quote, array $lines): void
    {
        foreach ($lines as $i => $line) {
            $qty = $line['quantity'] ?? 1;
            $price = $line['unit_price'];
            $discount = (float) ($line['discount'] ?? 0);
            $lineTotal = $qty * $price;
            $total = $discount > 0 ? $lineTotal * (1 - ($discount / 100)) : $lineTotal;

            QuoteLine::create([
                'quote_id' => $quote->id,
                'description' => $line['description'],
                'quantity' => $qty,
                'unit_price' => $price,
                'discount' => $discount,
                'total' => max(0, $total),
                'service_id' => $line['service_id'] ?? null,
                'sort_order' => $i,
            ]);
        }

        $quote->recalculate();

        // Sync suspended customer services for product lines
        $existingServiceIds = CustomerService::where('user_id', $quote->user_id)
            ->where('status', 'suspended')
            ->pluck('service_id')
            ->toArray();

        foreach ($quote->lines()->whereNotNull('service_id')->get() as $line) {
            if (!in_array($line->service_id, $existingServiceIds)) {
                CustomerService::create([
                    'user_id' => $quote->user_id,
                    'service_id' => $line->service_id,
                    'status' => 'suspended',
                    'price' => $line->unit_price,
                    'price_type' => Service::find($line->service_id)->price_type ?? 'eenmalig',
                    'start_date' => now(),
                ]);
            }
        }
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
            'status' => 'openstaand',
            'notes' => $quote->client_notes,
            'internal_notes' => $quote->internal_notes,
            'quote_id' => $quote->id,
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
        $quote->update(['status' => 'geweigerd']);

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
            'action' => 'geweigerd',
            'description' => "Offerte {$quote->quote_number} geweigerd door klant",
        ]);
    }
}
