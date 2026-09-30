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

    private function setupHostingService(): array
    {
        $service = Service::create([
            'title' => 'Hosting Start',
            'slug' => 'hosting-start',
            'service_type' => 'hosting',
            'fulfillment_type' => 'directadmin',
            'short_description' => 'Test hosting pakket',
            'description' => 'Test',
            'price' => 9.95,
            'price_type' => 'monthly',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $price = ServicePrice::create([
            'service_id' => $service->id,
            'billing_cycle' => 'monthly',
            'price' => 9.95,
            'is_enabled' => true,
        ]);

        return [$service, $price];
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
                'mollie_method' => 'ideal',
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

    public function test_customer_can_order_hosting_with_existing_domain(): void
    {
        [$hosting] = $this->setupHostingService();

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
            ->post(route('checkout.store', $hosting), [
                '_token' => 'test-token',
                'service_price_id' => ServicePrice::where('service_id', $hosting->id)->first()->id,
                'domain' => 'bestaand.nl',
                'domain_mode' => 'existing',
                'name' => $user->name,
                'street' => 'Teststraat',
                'house_number' => '1',
                'postal_code' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'payment_method' => 'payment_link',
                'mollie_method' => 'ideal',
                'terms' => '1',
            ]);

        $response->assertRedirect();

        $this->assertCount(1, CustomerService::where('user_id', $user->id)->get());
        $customerService = CustomerService::where('user_id', $user->id)->first();
        $this->assertSame('bestaand.nl', $customerService->domain);
        $this->assertSame($hosting->id, $customerService->service_id);
        $this->assertSame('pending_payment', $customerService->provisioning_status);
    }

    public function test_customer_can_order_hosting_with_new_domain(): void
    {
        [$hosting] = $this->setupHostingService();
        [$domainService] = $this->setupDomainServiceAndTld();

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
            ->post(route('checkout.store', $hosting), [
                '_token' => 'test-token',
                'service_price_id' => ServicePrice::where('service_id', $hosting->id)->first()->id,
                'domain' => 'nieuwdomein.nl',
                'domain_mode' => 'register',
                'name' => $user->name,
                'street' => 'Teststraat',
                'house_number' => '1',
                'postal_code' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'payment_method' => 'payment_link',
                'mollie_method' => 'ideal',
                'terms' => '1',
            ]);

        $response->assertRedirect();

        $services = CustomerService::where('user_id', $user->id)->get();
        $this->assertCount(2, $services);

        $hostingService = $services->first(fn ($s) => $s->service_id === $hosting->id);
        $domainServiceInstance = $services->first(fn ($s) => $s->service_id === $domainService->id);

        $this->assertNotNull($hostingService);
        $this->assertNotNull($domainServiceInstance);
        $this->assertSame('nieuwdomein.nl', $hostingService->domain);
        $this->assertSame('nieuwdomein.nl', $domainServiceInstance->domain);

        $registration = DomainRegistration::where('customer_service_id', $domainServiceInstance->id)->firstOrFail();
        $this->assertSame(DomainRegistration::TYPE_REGISTRATION, $registration->type);
        $this->assertSame(DomainRegistration::STATUS_AWAITING_PAYMENT, $registration->status);

        $order = Order::where('user_id', $user->id)->firstOrFail();
        $this->assertCount(2, $order->lines);
        $this->assertEqualsWithDelta(9.95 + 9.99, (float) $order->subtotal, 0.01);
    }

    public function test_customer_can_order_domain_transfer_with_hosting(): void
    {
        [$hosting] = $this->setupHostingService();
        [$domainService, $tld] = $this->setupDomainServiceAndTld();
        $tld->update(['transfer_price' => 5.99]);

        // TLD sync happens on DomainTldController update, but we can create it manually.
        ServicePrice::updateOrCreate(
            [
                'service_id' => $domainService->id,
                'billing_cycle' => 'one_time',
                'tld' => '.nl',
            ],
            ['price' => 5.99, 'is_enabled' => true]
        );

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
            ->post(route('checkout.store', $hosting), [
                '_token' => 'test-token',
                'service_price_id' => ServicePrice::where('service_id', $hosting->id)->first()->id,
                'domain' => 'teverhuizen.nl',
                'domain_mode' => 'transfer',
                'auth_code' => 'ABC123',
                'name' => $user->name,
                'street' => 'Teststraat',
                'house_number' => '1',
                'postal_code' => '1234AB',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'payment_method' => 'payment_link',
                'mollie_method' => 'ideal',
                'terms' => '1',
            ]);

        $response->assertRedirect();

        $services = CustomerService::where('user_id', $user->id)->get();
        $this->assertCount(2, $services);

        $domainServiceInstance = $services->first(fn ($s) => $s->service_id === $domainService->id);
        $this->assertNotNull($domainServiceInstance);
        $this->assertSame(5.99, (float) $domainServiceInstance->price);

        $registration = DomainRegistration::where('customer_service_id', $domainServiceInstance->id)->firstOrFail();
        $this->assertSame(DomainRegistration::TYPE_TRANSFER, $registration->type);
        $this->assertSame(DomainRegistration::STATUS_TRANSFER_PENDING, $registration->status);
        $this->assertSame('ABC123', $registration->auth_code);
    }

    public function test_domain_registration_is_not_listed_on_customer_services_index(): void
    {
        [$domainService] = $this->setupDomainServiceAndTld();
        [$hostingService, $hostingPrice] = $this->setupHostingService();

        $user = User::factory()->create([
            'role' => 'customer',
            'country' => 'NL',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
        ]);

        $this->actingAs($user);

        $domainPrice = ServicePrice::where('service_id', $domainService->id)->where('tld', '.nl')->first();

        CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $domainService->id,
            'service_price_id' => $domainPrice->id,
            'domain' => 'voorbeeld.nl',
            'status' => 'active',
            'price' => 9.99,
            'price_type' => 'jaarlijks',
            'billing_cycle' => 'yearly',
            'start_date' => now(),
        ]);

        CustomerService::create([
            'user_id' => $user->id,
            'service_id' => $hostingService->id,
            'service_price_id' => $hostingPrice->id,
            'status' => 'active',
            'price' => 9.95,
            'price_type' => 'maandelijks',
            'billing_cycle' => 'monthly',
            'start_date' => now(),
        ]);

        $response = $this->get(route('customer.services.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Hosting Start');
        $response->assertDontSeeText('Domeinregistratie');
    }
}
