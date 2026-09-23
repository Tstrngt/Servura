<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FinancialOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_sees_only_own_financial_records(): void
    {
        $customer = $this->customer('finance@servura.test');
        $other = $this->customer('other-finance@servura.test');
        $ownInvoice = $this->invoice($customer, 'FAC-2026-1001');
        $otherInvoice = $this->invoice($other, 'FAC-2026-1002');

        $response = $this->actingAs($customer)->get(route('customer.financial.index'));

        $response->assertOk();
        $response->assertSee($ownInvoice->invoice_number);
        $response->assertDontSee($otherInvoice->invoice_number);
    }

    public function test_customer_cannot_include_another_customers_invoice_in_batch(): void
    {
        $customer = $this->customer('finance@servura.test');
        $other = $this->customer('other-finance@servura.test');
        $ownInvoice = $this->invoice($customer, 'FAC-2026-1003');
        $otherInvoice = $this->invoice($other, 'FAC-2026-1004');

        $response = $this->actingAs($customer)
            ->from(route('customer.financial.index'))
            ->post(route('customer.financial.pay'), [
                'invoice_ids' => [$ownInvoice->id, $otherInvoice->id],
            ]);

        $response->assertRedirect(route('customer.financial.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('payment_batches', 0);
    }

    private function customer(string $email): User
    {
        return User::create([
            'name' => 'Financiële klant',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    private function invoice(User $user, string $number): Invoice
    {
        return Invoice::create([
            'invoice_number' => $number,
            'user_id' => $user->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'subtotal' => 100,
            'vat_amount' => 21,
            'total' => 121,
            'vat_percentage' => 21,
            'status' => 'verzonden',
        ]);
    }
}
