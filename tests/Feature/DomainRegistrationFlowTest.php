<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
use App\Models\CustomerService;
use App\Models\DomainRegistration;
use App\Models\DomainTld;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\User;
use App\Services\DomainRegistrationService;
use App\Services\Domains\DomainCheckResult;
use App\Services\Domains\TransIpProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Throwable;

class DomainRegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function setupDomainServiceAndTld(): array
    {
        BillingSetting::setValue('transip_enabled', '1');
        BillingSetting::setEncryptedValue('transip_private_key', 'fake-key');
        BillingSetting::setValue('transip_username', 'testuser');

        $service = Service::where('fulfillment_type', 'domain')->firstOrFail();
        $tld = DomainTld::factory()->create([
            'extension' => '.nl',
            'registration_price' => 9.99,
            'renewal_price' => 9.99,
            'is_active' => true,
        ]);
        $price = ServicePrice::create([
            'service_id' => $service->id,
            'billing_cycle' => 'yearly',
            'tld' => '.nl',
            'price' => 9.99,
            'is_enabled' => true,
        ]);

        return [$service, $tld, $price];
    }

    public function test_customer_can_order_domain_through_checkout(): void
    {
        [$service] = $this->setupDomainServiceAndTld();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => 'NL',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
        ]);

        $this->actingAs($user);

        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('checkout.store', $service), [
                '_token' => 'test-token',
                'service_price_id' => ServicePrice::where('tld', '.nl')->first()->id,
                'domain_name' => 'voorbeeld',
                'domain_tld' => '.nl',
                'name' => $user->name,
                'company' => 'Test BV',
                'phone' => '0612345678',
                'street' => 'Teststraat',
                'house_number' => '1',
                'postal_code' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'payment_method' => 'payment_link',
                'terms' => '1',
            ]);

        $response->assertRedirect();

        $customerService = CustomerService::where('user_id', $user->id)->firstOrFail();
        $this->assertSame('voorbeeld.nl', $customerService->domain);
        $this->assertSame('pending_payment', $customerService->provisioning_status);
        $this->assertNotNull($customerService->order);
        $this->assertNotNull($customerService->invoices()->first());
    }

    public function test_domain_is_registered_after_payment(): void
    {
        [$service] = $this->setupDomainServiceAndTld();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => 'NL',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
        ]);

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => ServicePrice::where('tld', '.nl')->first()->id,
            'domain' => 'voorbeeld.nl',
            'provisioning_status' => 'pending_payment',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => 9.99,
            'price_type' => 'yearly',
            'billing_cycle' => 'yearly',
            'start_date' => now(),
            'auto_renew' => true,
            'payment_method' => 'payment_link',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'user_id' => $user->id,
            'customer_service_id' => $customerService->id,
            'subtotal' => 9.99,
            'vat_amount' => 2.10,
            'total' => 12.09,
            'vat_percentage' => 21,
            'status' => 'betaald',
            'paid_at' => now(),
        ]);

        Order::create([
            'order_number' => Order::generateNumber(),
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => $customerService->service_price_id,
            'customer_service_id' => $customerService->id,
            'invoice_id' => $invoice->id,
            'billing_cycle' => 'yearly',
            'fulfillment_type' => 'domain',
            'subtotal' => 9.99,
            'vat_percentage' => 21,
            'billing_country' => 'NL',
            'vat_amount' => 2.10,
            'total' => 12.09,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $mock = Mockery::mock(TransIpProvider::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('checkAvailability')->with('voorbeeld.nl')->once()->andReturn(new DomainCheckResult('voorbeeld.nl', true, 'free', 'nl'));
        $mock->shouldReceive('registerDomain')->once();
        $this->app->instance(TransIpProvider::class, $mock);

        app(DomainRegistrationService::class)->register($customerService);

        $customerService->refresh();
        $registration = DomainRegistration::where('customer_service_id', $customerService->id)->firstOrFail();
        $this->assertNull($registration->error_message, 'Registration failed: '.$registration->error_message);
        $this->assertSame(DomainRegistration::STATUS_ACTIVE, $registration->status);
        $this->assertSame('active', $customerService->status);
        $this->assertSame('active', $customerService->provisioning_status);
    }

    public function test_registration_fails_when_domain_becomes_unavailable(): void
    {
        [$service] = $this->setupDomainServiceAndTld();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => 'NL',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
        ]);

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => ServicePrice::where('tld', '.nl')->first()->id,
            'domain' => 'voorbeeld.nl',
            'provisioning_status' => 'pending_payment',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => 9.99,
            'price_type' => 'yearly',
            'billing_cycle' => 'yearly',
            'start_date' => now(),
            'auto_renew' => true,
            'payment_method' => 'payment_link',
        ]);

        $mock = Mockery::mock(TransIpProvider::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('checkAvailability')->andReturn(new DomainCheckResult('voorbeeld.nl', false, 'notfree', 'nl'));
        $mock->shouldReceive('registerDomain')->never();
        $this->app->instance(TransIpProvider::class, $mock);

        app(DomainRegistrationService::class)->register($customerService);

        $registration = DomainRegistration::where('customer_service_id', $customerService->id)->firstOrFail();
        $this->assertSame(DomainRegistration::STATUS_REGISTRATION_FAILED, $registration->status);
        $this->assertStringContainsString('inmiddels niet meer beschikbaar', $registration->error_message);
    }

    public function test_registration_is_idempotent(): void
    {
        [$service] = $this->setupDomainServiceAndTld();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => 'NL',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
        ]);

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => ServicePrice::where('tld', '.nl')->first()->id,
            'domain' => 'voorbeeld.nl',
            'provisioning_status' => 'pending_payment',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => 9.99,
            'price_type' => 'yearly',
            'billing_cycle' => 'yearly',
            'start_date' => now(),
            'auto_renew' => true,
            'payment_method' => 'payment_link',
        ]);

        DomainRegistration::create([
            'user_id' => $user->id,
            'order_id' => null,
            'customer_service_id' => $customerService->id,
            'domain_name' => 'voorbeeld.nl',
            'tld' => '.nl',
            'status' => DomainRegistration::STATUS_ACTIVE,
            'registration_price' => 9.99,
        ]);

        $mock = Mockery::mock(TransIpProvider::class);
        $mock->shouldReceive('checkAvailability')->never();
        $mock->shouldReceive('registerDomain')->never();
        $this->app->instance(TransIpProvider::class, $mock);

        app(DomainRegistrationService::class)->register($customerService);

        $this->assertSame(DomainRegistration::STATUS_ACTIVE, DomainRegistration::first()->status);
    }

    public function test_registration_fails_when_customer_details_are_missing(): void
    {
        [$service] = $this->setupDomainServiceAndTld();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => null,
            'street' => null,
        ]);

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => ServicePrice::where('tld', '.nl')->first()->id,
            'domain' => 'voorbeeld.nl',
            'provisioning_status' => 'pending_payment',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => 9.99,
            'price_type' => 'yearly',
            'billing_cycle' => 'yearly',
            'start_date' => now(),
            'auto_renew' => true,
            'payment_method' => 'payment_link',
        ]);

        $mock = Mockery::mock(TransIpProvider::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('checkAvailability')->never();
        $mock->shouldReceive('registerDomain')->never();
        $this->app->instance(TransIpProvider::class, $mock);

        app(DomainRegistrationService::class)->register($customerService);

        $registration = DomainRegistration::firstOrFail();
        $this->assertSame(DomainRegistration::STATUS_REGISTRATION_FAILED, $registration->status);
        $this->assertStringContainsString('Ontbrekende klantgegevens', $registration->error_message);
    }

    public function test_registration_fails_on_transip_timeout(): void
    {
        [$service] = $this->setupDomainServiceAndTld();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => 'NL',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
        ]);

        $customerService = CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'service_price_id' => ServicePrice::where('tld', '.nl')->first()->id,
            'domain' => 'voorbeeld.nl',
            'provisioning_status' => 'pending_payment',
            'status' => 'suspended',
            'suspension_reason' => 'pending_payment',
            'price' => 9.99,
            'price_type' => 'yearly',
            'billing_cycle' => 'yearly',
            'start_date' => now(),
            'auto_renew' => true,
            'payment_method' => 'payment_link',
        ]);

        $mock = Mockery::mock(TransIpProvider::class);
        $mock->shouldReceive('isConfigured')->andReturn(true);
        $mock->shouldReceive('checkAvailability')->andReturn(new DomainCheckResult('voorbeeld.nl', true, 'free', 'nl'));
        $mock->shouldReceive('registerDomain')->andThrow(new class('TransIP timeout') extends \Exception {});
        $this->app->instance(TransIpProvider::class, $mock);

        app(DomainRegistrationService::class)->register($customerService);

        $registration = DomainRegistration::firstOrFail();
        $this->assertSame(DomainRegistration::STATUS_REGISTRATION_FAILED, $registration->status);
        $this->assertStringContainsString('TransIP timeout', $registration->error_message);
    }
}
