<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
use App\Models\CustomerService;
use App\Models\DomainRegistration;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Service;
use App\Models\TransactionLog;
use App\Models\User;
use App\Services\Domains\DomainCheckResult;
use App\Services\Domains\TransIpProvider;
use App\Services\MolliePaymentService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mollie\Api\MollieApiClient;
use Mollie\Laravel\Facades\Mollie;
use Tests\TestCase;

class PaidInvoiceDomainRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_paid_invoice_registers_domain_from_order_lines_when_invoice_lines_lost_service_link(): void
    {
        [$customerService, $invoice] = $this->editedDomainOrder();
        $this->expectProviderRegistration();

        $invoice->update(['status' => 'betaald', 'paid_at' => now()]);
        app(MolliePaymentService::class)->finalizePaidInvoice($invoice);

        $this->assertSame(DomainRegistration::STATUS_ACTIVE, $this->registrationFor($customerService)->status);
        $this->assertSame('active', $customerService->fresh()->provisioning_status);
    }

    public function test_editing_draft_invoice_keeps_customer_service_link_on_lines(): void
    {
        [$customerService, $invoice] = $this->editedDomainOrder();
        $otherCustomerService = CustomerService::create($this->customerServiceAttributes(User::factory()->create()));
        $invoice->lines()->delete();
        InvoiceLine::create([
            'invoice_id' => $invoice->id, 'description' => 'Domeinregistratie - voorbeeld.nl', 'quantity' => 1,
            'unit_price' => 9.99, 'total' => 9.99, 'customer_service_id' => $customerService->id, 'sort_order' => 0,
        ]);
        $invoice->update(['status' => 'concept']);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->actingAs($admin)->put(route('admin.financial.invoices.update', $invoice), [
            'lines' => [
                ['description' => 'Domeinregistratie - voorbeeld.nl', 'quantity' => 1, 'unit_price' => 9.99, 'customer_service_id' => $customerService->id],
                ['description' => 'Rest betaling', 'quantity' => 1, 'unit_price' => 15, 'customer_service_id' => $otherCustomerService->id],
            ],
        ])->assertSessionHasNoErrors();

        $lines = $invoice->lines()->orderBy('sort_order')->get();
        $this->assertSame($customerService->id, $lines[0]->customer_service_id);
        $this->assertNull($lines[1]->customer_service_id, 'Services of other customers must not be linked.');
    }

    public function test_webhook_for_superseded_unpaid_payment_is_ignored(): void
    {
        [, $invoice] = $this->editedDomainOrder();
        $this->startPayments($invoice, 'tr_old', 'tr_new');
        $this->fakeMolliePayment('tr_old', $invoice, 'expired');

        app(MolliePaymentService::class)->handleWebhook('tr_old');

        $invoice->refresh();
        $this->assertSame('verzonden', $invoice->status);
        $this->assertSame('tr_new', $invoice->mollie_payment_id);
    }

    public function test_webhook_for_superseded_paid_payment_still_settles_invoice_and_registers_domain(): void
    {
        [$customerService, $invoice] = $this->editedDomainOrder();
        $this->startPayments($invoice, 'tr_old', 'tr_new');
        $this->fakeMolliePayment('tr_old', $invoice, 'paid');
        $this->expectProviderRegistration();

        app(MolliePaymentService::class)->handleWebhook('tr_old');

        $this->assertSame('betaald', $invoice->fresh()->status);
        $this->assertSame(DomainRegistration::STATUS_ACTIVE, $this->registrationFor($customerService)->status);
    }

    public function test_webhook_for_unknown_payment_is_rejected(): void
    {
        [, $invoice] = $this->editedDomainOrder();
        $this->startPayments($invoice, 'tr_new');
        $this->fakeMolliePayment('tr_foreign', $invoice, 'paid');

        $this->expectException(\RuntimeException::class);
        app(MolliePaymentService::class)->handleWebhook('tr_foreign');
    }

    public function test_owner_can_start_paid_registration_that_never_reached_provider(): void
    {
        [$customerService, $invoice] = $this->editedDomainOrder();
        $invoice->update(['status' => 'betaald', 'paid_at' => now()]);
        $this->expectProviderRegistration();
        $owner = User::factory()->create(['role' => 'admin']);
        $owner->role = 'owner';
        $registration = $this->registrationFor($customerService);

        $this->actingAs($owner)->get(route('admin.domains.show', $registration))->assertSee('Nu bij provider uitvoeren');
        $this->actingAs($owner)->post(route('admin.domains.retry', $registration))->assertSessionHas('success');

        $this->assertSame(DomainRegistration::STATUS_ACTIVE, $registration->fresh()->status);
    }

    public function test_owner_cannot_start_unpaid_registration(): void
    {
        [$customerService] = $this->editedDomainOrder();
        $owner = User::factory()->create(['role' => 'admin']);
        $owner->role = 'owner';

        $this->actingAs($owner)->post(route('admin.domains.retry', $this->registrationFor($customerService)))->assertStatus(422);
    }

    /**
     * Mirrors an order whose draft invoice was edited, leaving lines without customer_service_id.
     */
    private function editedDomainOrder(): array
    {
        BillingSetting::setValue('domain_provider', 'transip');
        BillingSetting::setValue('transip_enabled', '1');
        BillingSetting::setEncryptedValue('transip_private_key', 'fake-key');
        BillingSetting::setValue('transip_username', 'testuser');

        $user = User::factory()->create([
            'role' => 'customer', 'country' => 'NL', 'street' => 'Teststraat', 'house_number' => '1',
            'postal_code' => '1234AB', 'city' => 'Amsterdam',
        ]);
        $customerService = CustomerService::create($this->customerServiceAttributes($user));

        DomainRegistration::create([
            'user_id' => $user->id, 'customer_service_id' => $customerService->id, 'type' => DomainRegistration::TYPE_REGISTRATION,
            'domain_name' => 'voorbeeld.nl', 'tld' => '.nl', 'status' => DomainRegistration::STATUS_AWAITING_PAYMENT,
            'provider' => 'transip', 'registration_price' => 9.99, 'auto_renew' => true,
        ]);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(), 'invoice_date' => now(), 'due_date' => now()->addDays(14),
            'user_id' => $user->id, 'customer_service_id' => null, 'subtotal' => 24.99, 'vat_amount' => 0,
            'total' => 24.99, 'vat_percentage' => 0, 'status' => 'verzonden',
        ]);
        InvoiceLine::create([
            'invoice_id' => $invoice->id, 'description' => 'Domeinregistratie - voorbeeld.nl', 'quantity' => 1,
            'unit_price' => 9.99, 'total' => 9.99, 'customer_service_id' => null, 'sort_order' => 0,
        ]);
        InvoiceLine::create([
            'invoice_id' => $invoice->id, 'description' => 'Rest betaling', 'quantity' => 1,
            'unit_price' => 15, 'total' => 15, 'customer_service_id' => null, 'sort_order' => 1,
        ]);

        $order = Order::create([
            'order_number' => Order::generateNumber(), 'user_id' => $user->id, 'service_id' => $customerService->service_id,
            'customer_service_id' => null, 'invoice_id' => $invoice->id, 'billing_cycle' => 'yearly',
            'fulfillment_type' => 'domain', 'subtotal' => 9.99, 'vat_percentage' => 0, 'billing_country' => 'NL',
            'vat_amount' => 0, 'total' => 9.99, 'status' => 'pending_payment',
        ]);
        OrderLine::create([
            'order_id' => $order->id, 'service_id' => $customerService->service_id, 'customer_service_id' => $customerService->id,
            'description' => 'Domeinregistratie - voorbeeld.nl', 'quantity' => 1, 'unit_price' => 9.99, 'total' => 9.99, 'sort_order' => 0,
        ]);
        DomainRegistration::where('customer_service_id', $customerService->id)->update(['order_id' => $order->id]);

        return [$customerService, $invoice];
    }

    private function customerServiceAttributes(User $user): array
    {
        return [
            'user_id' => $user->id, 'service_id' => Service::where('fulfillment_type', 'domain')->firstOrFail()->id,
            'domain' => 'voorbeeld.nl', 'provisioning_status' => 'pending_payment', 'status' => 'suspended',
            'suspension_reason' => 'pending_payment', 'price' => 9.99, 'price_type' => 'yearly', 'billing_cycle' => 'yearly',
            'start_date' => now(), 'auto_renew' => true, 'payment_method' => 'payment_link',
        ];
    }

    private function registrationFor(CustomerService $customerService): DomainRegistration
    {
        return DomainRegistration::where('customer_service_id', $customerService->id)->firstOrFail();
    }

    private function expectProviderRegistration(): void
    {
        $mock = Mockery::mock(TransIpProvider::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('checkAvailability')->with('voorbeeld.nl')->once()->andReturn(new DomainCheckResult('voorbeeld.nl', true, 'free', 'nl'));
        $mock->shouldReceive('registerDomain')->once();
        $this->app->instance(TransIpProvider::class, $mock);
    }

    private function startPayments(Invoice $invoice, string ...$paymentIds): void
    {
        foreach ($paymentIds as $paymentId) {
            TransactionLog::create([
                'user_id' => $invoice->user_id, 'loggable_type' => Invoice::class, 'loggable_id' => $invoice->id,
                'action' => 'betaling_gestart', 'description' => 'Mollie betaling gestart',
                'metadata' => ['mollie_payment_id' => $paymentId, 'method' => 'ideal'],
            ]);
            $invoice->update(['mollie_payment_id' => $paymentId]);
        }
    }

    private function fakeMolliePayment(string $paymentId, Invoice $invoice, string $status): void
    {
        $payment = new class($paymentId, $invoice, $status)
        {
            public object $metadata;

            public object $amount;

            public string $method = 'ideal';

            public function __construct(public string $id, Invoice $invoice, public string $status)
            {
                $this->metadata = (object) ['invoice_id' => $invoice->id];
                $this->amount = (object) ['value' => number_format((float) $invoice->total, 2, '.', '')];
            }

            public function isPaid(): bool
            {
                return $this->status === 'paid';
            }

            public function isFailed(): bool
            {
                return $this->status === 'failed';
            }

            public function isExpired(): bool
            {
                return $this->status === 'expired';
            }

            public function isCanceled(): bool
            {
                return $this->status === 'canceled';
            }
        };

        $payments = Mockery::mock();
        $payments->shouldReceive('get')->with($paymentId)->andReturn($payment);
        $api = Mockery::mock(MollieApiClient::class);
        $api->payments = $payments;
        Mollie::shouldReceive('api')->andReturn($api);
    }
}
