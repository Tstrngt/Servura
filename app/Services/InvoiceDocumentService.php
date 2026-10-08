<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\Invoice;
use App\Models\InvoiceDesignVersion;
use App\Models\InvoiceLine;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

class InvoiceDocumentService
{
    public function defaultSettings(): array
    {
        return [
            'logo_variant' => 'dark',
            'accent_color' => '#0ea5e9',
            'primary_color' => '#071d3b',
            'footer_text' => 'Vermeld bij betaling altijd het factuurnummer.',
            'payment_text' => 'Betaal uiterlijk op de vervaldatum via de betaallink of per bankoverschrijving.',
            'iban' => BillingSetting::valueFor('invoice_iban', ''),
            'account_holder' => BillingSetting::valueFor('invoice_account_holder', config('company.trade_name', 'Servura')),
        ];
    }

    public function settingsFor(?Invoice $invoice = null, bool $draft = false): array
    {
        $version = $draft ? InvoiceDesignVersion::draft() : ($invoice?->designVersion ?: InvoiceDesignVersion::current());

        return array_merge($this->defaultSettings(), $version?->settings ?? []);
    }

    public function validateIssuer(): void
    {
        $required = [
            'Bedrijfsnaam' => config('company.legal_name'),
            'Adres' => config('company.address'),
            'Postcode' => config('company.postal_code'),
            'Plaats' => config('company.city'),
            'KvK-nummer' => config('company.kvk_number'),
            'Btw-identificatienummer' => config('company.vat_number'),
            'Zakelijk e-mailadres' => config('company.email'),
            'Website' => config('company.website'),
        ];
        $missing = array_keys(array_filter($required, fn ($value) => blank($value) || str_contains((string) $value, '[')));
        if ($missing) {
            throw ValidationException::withMessages(['invoice' => 'Vul eerst de verplichte factuurgegevens in: '.implode(', ', $missing).'.']);
        }
    }

    public function issue(Invoice $invoice): void
    {
        $this->validateIssuer();
        $version = InvoiceDesignVersion::current();
        $invoice->loadMissing('user');
        $invoice->update([
            'invoice_design_version_id' => $version?->id,
            'issuer_snapshot' => $this->issuer(),
            'customer_snapshot' => $this->customer($invoice->user),
        ]);
    }

    public function pdf(Invoice $invoice, bool $draft = false)
    {
        $invoice->loadMissing(['lines.customerService.service', 'user', 'transactions']);
        $view = ($draft || $invoice->invoice_design_version_id) ? 'pdf.invoice-professional' : 'pdf.invoice';

        return Pdf::loadView($view, $this->data($invoice, $draft))->setPaper('a4');
    }

    public function data(Invoice $invoice, bool $draft = false): array
    {
        $paid = (float) $invoice->transactions->where('status', 'voltooid')->where('type', 'inkomst')->sum('amount');
        $refunded = (float) $invoice->transactions->where('status', 'terugbetaald')->sum('amount');
        $paid = max(0, $paid - $refunded);
        $vatGroups = $invoice->lines->groupBy(fn ($line) => (string) ($line->vat_percentage ?? $invoice->vat_percentage))
            ->map(function ($lines, $rate) {
                $base = $lines->sum(fn ($line) => (float) $line->total - (float) ($line->discount_amount ?? 0));
                return ['rate' => (float) $rate, 'base' => $base, 'amount' => round($base * ((float) $rate / 100), 2)];
            })->values();

        return [
            'invoice' => $invoice,
            'settings' => $this->settingsFor($invoice, $draft),
            'issuer' => $invoice->issuer_snapshot ?: $this->issuer(),
            'customer' => $invoice->customer_snapshot ?: $this->customer($invoice->user),
            'paidAmount' => $paid,
            'outstandingAmount' => max(0, (float) $invoice->total - $paid),
            'vatGroups' => $vatGroups,
            'logoDark' => public_path('images/servura-logo-dark.png'),
            'logoLight' => public_path('images/servura-logo-light.png'),
            'isPreview' => $draft,
        ];
    }

    public function preview(string $scenario): Invoice
    {
        $business = in_array($scenario, ['business', 'multiple', 'discount', 'multipage', 'credit'], true);
        $user = new User([
            'name' => $business ? 'Marloes van den Berg' : 'Daan Vermeer',
            'email' => $business ? 'administratie@rijnstreek.test' : 'daan.vermeer@example.test',
            'company' => $business ? 'STC De Rijnstreek B.V.' : null,
            'street' => 'Rijndijk', 'house_number' => '42', 'postal_code' => '2394 AP', 'city' => 'Hazerswoude-Rijndijk',
            'country' => 'Nederland', 'vat_number' => $business ? 'NL860123456B01' : null,
        ]);
        $invoice = new Invoice([
            'invoice_number' => $scenario === 'credit' ? 'CR-2026-0001' : 'FAC-2026-0001',
            'document_type' => $scenario === 'credit' ? 'credit' : 'invoice',
            'invoice_date' => now()->startOfMonth(), 'due_date' => now()->startOfMonth()->addDays(14),
            'status' => $scenario === 'paid' ? 'betaald' : 'openstaand', 'vat_percentage' => 21,
            'payment_url' => url('/voorbeeld-betaling/FAC-2026-0001'),
            'paid_at' => $scenario === 'paid' ? now()->startOfMonth()->addDays(3) : null,
        ]);
        $invoice->setRelation('user', $user);
        $count = $scenario === 'multipage' ? 34 : ($scenario === 'multiple' ? 4 : 1);
        $lines = collect();
        for ($i = 0; $i < $count; $i++) {
            $price = $scenario === 'credit' ? -49.95 : [49.95, 12.50, 850, 29.95][$i % 4];
            $lines->push(new InvoiceLine([
                'description' => ['Webhosting Business', 'Domeinregistratie .nl', 'Webdesign werkzaamheden', 'Onderhoud en support'][$i % 4],
                'service_reference' => $i % 4 < 2 ? 'stc-de-rijnstreek.nl' : 'Project STC-2026',
                'quantity' => 1, 'unit_price' => $price, 'total' => $price,
                'vat_percentage' => $i % 4 === 1 ? 0 : 21,
                'discount_amount' => $scenario === 'discount' ? 5 : 0,
                'period_start' => now()->startOfMonth(), 'period_end' => now()->startOfMonth()->addYear()->subDay(),
            ]));
        }
        $invoice->setRelation('lines', $lines);
        $subtotal = $lines->sum('total');
        $discount = $lines->sum('discount_amount');
        $vat = $lines->sum(fn ($line) => round(((float) $line->total - (float) $line->discount_amount) * ((float) $line->vat_percentage / 100), 2));
        $invoice->subtotal = $subtotal;
        $invoice->discount_amount = $discount;
        $invoice->vat_amount = $vat;
        $invoice->total = $subtotal - $discount + $vat;
        $transactions = collect();
        if ($scenario === 'paid') {
            $transactions->push(new Transaction(['amount' => $invoice->total, 'type' => 'inkomst', 'status' => 'voltooid', 'transaction_date' => $invoice->paid_at]));
        } elseif ($scenario === 'partial') {
            $transactions->push(new Transaction(['amount' => 25, 'type' => 'inkomst', 'status' => 'voltooid', 'transaction_date' => now()]));
        }
        $invoice->setRelation('transactions', $transactions);
        return $invoice;
    }

    private function issuer(): array
    {
        return [
            'name' => config('company.legal_name'), 'trade_name' => config('company.trade_name'), 'address' => config('company.address'),
            'postal_code' => config('company.postal_code'), 'city' => config('company.city'), 'country' => config('company.country'),
            'kvk' => config('company.kvk_number'), 'vat' => config('company.vat_number'), 'email' => config('company.email'),
            'website' => config('company.website'), 'phone' => config('company.phone'),
        ];
    }

    private function customer(User $user): array
    {
        return [
            'number' => 'KL-'.str_pad((string) ($user->id ?: 1), 6, '0', STR_PAD_LEFT), 'name' => $user->name, 'company' => $user->company,
            'address' => trim($user->street.' '.$user->house_number), 'postal_code' => $user->postal_code, 'city' => $user->city,
            'country' => $user->country, 'vat' => $user->vat_number, 'email' => $user->email,
        ];
    }
}
