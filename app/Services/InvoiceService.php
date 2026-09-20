<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\CustomerService;
use App\Models\TransactionLog;
use App\Models\User;

class InvoiceService
{
    /**
     * Generate an invoice for a customer service assignment.
     */
    public function createFromCustomerService(CustomerService $customerService, ?int $performedBy = null, ?float $vatRate = null): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'user_id' => $customerService->user_id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(BillingSetting::integer('invoice_due_days', 14)),
            'vat_percentage' => $vatRate ?? BillingSetting::decimal('default_vat_rate', 21.0),
            'customer_service_id' => $customerService->id,
            'status' => 'concept',
        ]);

        $service = $customerService->service;
        $price = $customerService->price ?? $service->price ?? 0;

        $priceLabel = match ($customerService->price_type ?? $service->price_type) {
            'monthly', 'maandelijks' => ' (maandelijks)',
            'quarterly' => ' (per kwartaal)',
            'semiannual' => ' (ieder halfjaar)',
            'yearly', 'jaarlijks' => ' (jaarlijks)',
            'biennial' => ' (per 2 jaar)',
            'triennial' => ' (per 3 jaar)',
            default => '',
        };

        InvoiceLine::create([
            'invoice_id' => $invoice->id,
            'description' => $service->title . $priceLabel,
            'quantity' => 1,
            'unit_price' => $price,
            'total' => $price,
            'customer_service_id' => $customerService->id,
            'sort_order' => 0,
        ]);

        $invoice->recalculate();

        TransactionLog::create([
            'user_id' => $customerService->user_id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'aangemaakt',
            'description' => "Factuur {$invoice->invoice_number} aangemaakt voor {$service->title}",
            'performed_by' => $performedBy,
        ]);

        return $invoice;
    }

    /**
     * Create a manual invoice for a customer.
     */
    public function createManual(User $customer, array $lines, ?string $notes = null, ?int $performedBy = null): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'user_id' => $customer->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(BillingSetting::integer('invoice_due_days', 14)),
            'vat_percentage' => BillingSetting::decimal('default_vat_rate', 21.0),
            'status' => 'concept',
            'notes' => $notes,
        ]);

        foreach ($lines as $i => $line) {
            $total = ($line['quantity'] ?? 1) * $line['unit_price'];
            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'description' => $line['description'],
                'quantity' => $line['quantity'] ?? 1,
                'unit_price' => $line['unit_price'],
                'total' => $total,
                'customer_service_id' => $line['customer_service_id'] ?? null,
                'sort_order' => $i,
            ]);
        }

        $invoice->recalculate();

        TransactionLog::create([
            'user_id' => $customer->id,
            'loggable_type' => Invoice::class,
            'loggable_id' => $invoice->id,
            'action' => 'aangemaakt',
            'description' => "Factuur {$invoice->invoice_number} handmatig aangemaakt",
            'performed_by' => $performedBy,
        ]);

        return $invoice;
    }
}
