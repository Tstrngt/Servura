<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InvoicePdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_download_own_invoice_as_pdf(): void
    {
        $customer = $this->customer('pdf@servura.test');
        $invoice = $this->invoice($customer);

        $response = $this->actingAs($customer)->get(route('customer.invoices.download', $invoice));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString($invoice->invoice_number.'.pdf', $response->headers->get('content-disposition'));
    }

    public function test_customer_cannot_download_another_customers_invoice(): void
    {
        $owner = $this->customer('eigenaar-pdf@servura.test');
        $other = $this->customer('ander-pdf@servura.test');
        $invoice = $this->invoice($owner);

        $this->actingAs($other)
            ->get(route('customer.invoices.download', $invoice))
            ->assertForbidden();
    }

    private function customer(string $email): User
    {
        return User::create([
            'name' => 'PDF Testklant',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    private function invoice(User $customer): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'user_id' => $customer->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'subtotal' => 100,
            'vat_amount' => 21,
            'total' => 121,
            'vat_percentage' => 21,
            'status' => 'verzonden',
        ]);
        $invoice->lines()->create([
            'description' => 'Website onderhoud',
            'quantity' => 1,
            'unit_price' => 100,
            'total' => 100,
            'sort_order' => 1,
        ]);

        return $invoice;
    }
}
