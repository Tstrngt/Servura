<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\InvoiceDesignVersion;
use App\Models\Transaction;
use App\Models\User;
use App\Services\InvoiceDocumentService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfessionalInvoiceSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_calculates_multiple_vat_rates_and_discount_per_line(): void
    {
        $invoice = $this->invoice();
        $invoice->lines()->createMany([
            ['description' => 'Hosting', 'quantity' => 1, 'unit_price' => 100, 'total' => 100, 'vat_percentage' => 21, 'discount_amount' => 10, 'sort_order' => 0],
            ['description' => 'Domein', 'quantity' => 1, 'unit_price' => 25, 'total' => 25, 'vat_percentage' => 0, 'discount_amount' => 0, 'sort_order' => 1],
        ]);

        $invoice->recalculate();
        $invoice->refresh();

        $this->assertSame('125.00', $invoice->subtotal);
        $this->assertSame('10.00', $invoice->discount_amount);
        $this->assertSame('18.90', $invoice->vat_amount);
        $this->assertSame('133.90', $invoice->total);
    }

    public function test_document_data_uses_completed_payments_for_outstanding_amount(): void
    {
        $invoice = $this->invoice(['total' => 121]);
        Transaction::create([
            'transaction_number' => Transaction::generateNumber(), 'user_id' => $invoice->user_id, 'invoice_id' => $invoice->id,
            'amount' => 40, 'type' => 'inkomst', 'payment_method' => 'bank', 'status' => 'voltooid',
            'description' => 'Deelbetaling', 'transaction_date' => now(),
        ]);
        $invoice->load(['lines', 'transactions', 'user']);

        $data = app(InvoiceDocumentService::class)->data($invoice);

        $this->assertSame(40.0, $data['paidAmount']);
        $this->assertSame(81.0, $data['outstandingAmount']);
    }

    public function test_preview_scenarios_include_credit_and_multipage_data(): void
    {
        $documents = app(InvoiceDocumentService::class);

        $this->assertSame('credit', $documents->preview('credit')->document_type);
        $this->assertCount(34, $documents->preview('multipage')->lines);
        $this->assertNotEmpty($documents->preview('business')->user->vat_number);
    }

    public function test_finance_permissions_allow_invoice_download_but_not_design_publication(): void
    {
        $finance = User::factory()->create(['role' => 'employee']);
        $this->seed(RolesAndPermissionsSeeder::class);
        $finance->assignRole('finance');

        $invoice = $this->invoice();
        $this->actingAs($finance)->get(route('admin.financial.invoices.download', $invoice))
            ->assertSuccessful();
        $this->actingAs($finance)->post(route('admin.financial.invoice-design.publish'))
            ->assertForbidden();
    }

    public function test_authorized_admin_can_publish_draft_without_changing_existing_invoice_version(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->seed(RolesAndPermissionsSeeder::class);
        $draft = InvoiceDesignVersion::draft();
        config()->set('company', [
            'legal_name' => 'Servura B.V.', 'trade_name' => 'Servura', 'address' => 'Teststraat 1', 'postal_code' => '1234 AB',
            'city' => 'Leiden', 'country' => 'Nederland', 'kvk_number' => '12345678', 'vat_number' => 'NL123456789B01',
            'email' => 'facturen@servura.nl', 'website' => 'https://servura.nl', 'phone' => '',
        ]);

        $this->actingAs($admin)->post(route('admin.financial.invoice-design.publish'))->assertSessionHasNoErrors();

        $this->assertSame('published', $draft->fresh()->status);
        $this->assertDatabaseHas('audit_logs', ['action' => 'invoice_design_published']);
    }

    public function test_draft_design_does_not_replace_published_version(): void
    {
        $published = InvoiceDesignVersion::create([
            'version' => 1, 'status' => 'published', 'settings' => ['primary_color' => '#071d3b'], 'published_at' => now(),
        ]);
        $draft = InvoiceDesignVersion::draft();
        $draft->update(['settings' => ['primary_color' => '#123456']]);

        $this->assertTrue($published->is(InvoiceDesignVersion::current()));
        $this->assertSame('draft', $draft->status);
    }

    private function invoice(array $attributes = []): Invoice
    {
        $user = User::factory()->create(['role' => 'customer']);

        return Invoice::create(array_merge([
            'invoice_number' => Invoice::generateNumber(), 'user_id' => $user->id, 'invoice_date' => now(),
            'due_date' => now()->addDays(14), 'subtotal' => 0, 'vat_amount' => 0, 'total' => 0,
            'vat_percentage' => 21, 'status' => 'concept',
        ], $attributes));
    }
}
