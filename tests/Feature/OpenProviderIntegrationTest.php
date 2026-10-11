<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
use App\Models\DomainTld;
use App\Models\DomainTldPrice;
use App\Models\User;
use App\Services\OpenProviderPriceSyncService;
use App\Services\Domains\DomainProviderFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenProviderIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        BillingSetting::setValue('domain_provider', 'openprovider');
        BillingSetting::setValue('openprovider_enabled', '1');
        BillingSetting::setValue('openprovider_environment', 'sandbox');
        BillingSetting::setValue('openprovider_username', 'api@example.test');
        BillingSetting::setEncryptedValue('openprovider_password', 'secret');
        BillingSetting::setValue('openprovider_customer_handle', 'XX123456-XX');
    }

    public function test_price_sync_applies_markup_and_promotion_per_operation(): void
    {
        BillingSetting::setValue('openprovider_customer_handle', '');
        $tld = DomainTld::factory()->create(['extension' => '.nl', 'registration_price' => 0, 'renewal_price' => 0, 'transfer_price' => 0]);
        Http::fake(function ($request) {
            if (str_ends_with($request->url(), '/auth/login')) {
                return Http::response(['code' => 0, 'data' => ['token' => 'sandbox-token']], 200);
            }
            if (str_contains($request->url(), '/domains/prices')) {
                $promotion = $request['operation'] === 'create';
                return Http::response(['code' => 0, 'data' => [
                    'is_premium' => false,
                    'is_promotion' => $promotion,
                    'price' => ['reseller' => ['currency' => 'EUR', 'price' => $promotion ? 8 : 10]],
                    'tier_price' => ['reseller' => ['currency' => 'EUR', 'price' => 10]],
                    'promotion_data' => $promotion ? ['start_date' => (string) now()->subDay()->timestamp, 'end_date' => (string) now()->addDay()->timestamp] : null,
                ]], 200);
            }
            return Http::response(['code' => 0, 'data' => []], 200);
        });

        app(OpenProviderPriceSyncService::class)->run();

        $create = DomainTldPrice::where('domain_tld_id', $tld->id)->where('operation', 'create')->firstOrFail();
        $this->assertSame('8.0000', $create->effective_cost);
        $this->assertSame('8.80', $create->calculated_sale_price);
        $this->assertTrue($create->is_promotion);
        $this->assertSame('8.80', $tld->fresh()->registration_price);
        $this->assertSame('11.00', $tld->fresh()->renewal_price);
        $this->assertSame('11.00', $tld->fresh()->transfer_price);
    }

    public function test_factory_resolves_openprovider_and_checks_domain_through_sandbox_api(): void
    {
        Http::fake([
            'https://api.sandbox.openprovider.nl/v1beta/auth/login' => Http::response(['code' => 0, 'data' => ['token' => 'sandbox-token']], 200),
            'https://api.sandbox.openprovider.nl/v1beta/domains/check' => Http::response([
                'code' => 0,
                'data' => ['results' => [['domain' => 'voorbeeld.nl', 'status' => 'free']]],
            ], 200),
        ]);

        $provider = DomainProviderFactory::default();
        $result = $provider->checkAvailability('voorbeeld.nl');

        $this->assertSame('Openprovider', $provider->name());
        $this->assertTrue($result->available);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.sandbox.openprovider.nl/v1beta/domains/check'
            && $request->hasHeader('Authorization', 'Bearer sandbox-token')
            && $request['domains'][0] === ['name' => 'voorbeeld', 'extension' => 'nl']);
    }

    public function test_openprovider_customer_handle_is_created_and_stored_for_servura_customer(): void
    {
        $user = User::factory()->create([
            'name' => 'Jan Jansen',
            'email' => 'jan@example.test',
            'phone' => '0612345678',
            'street' => 'Teststraat',
            'house_number' => '1',
            'postal_code' => '1234AB',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);
        Http::fake([
            'https://api.sandbox.openprovider.nl/v1beta/auth/login' => Http::response(['code' => 0, 'data' => ['token' => 'sandbox-token']], 200),
            'https://api.sandbox.openprovider.nl/v1beta/customers' => Http::response(['code' => 0, 'data' => ['handle' => 'NL654321-NL']], 200),
        ]);

        $handle = DomainProviderFactory::default()->ensureCustomerHandle($user);

        $this->assertSame('NL654321-NL', $handle);
        $this->assertSame('NL654321-NL', $user->fresh()->openprovider_handle);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.sandbox.openprovider.nl/v1beta/customers'
            && $request['name']['first_name'] === 'Jan'
            && $request['address']['country'] === 'NL'
            && $request['phone'] === [
                'country_code' => '+31',
                'area_code' => '6',
                'subscriber_number' => '12345678',
            ]);
    }

    public function test_sandbox_nameserver_update_is_simulated_without_slow_provider_request(): void
    {
        Http::fake();

        DomainProviderFactory::default()->setNameservers('voorbeeld.nl', [
            ['hostname' => 'ns1.voorbeeld.nl'],
            ['hostname' => 'ns2.voorbeeld.nl'],
        ]);

        Http::assertNothingSent();
    }

    public function test_domain_info_and_holder_contacts_are_normalized_for_customer_portal(): void
    {
        Http::fake([
            'https://api.sandbox.openprovider.nl/v1beta/auth/login' => Http::response(['code' => 0, 'data' => ['token' => 'sandbox-token']], 200),
            'https://api.sandbox.openprovider.nl/v1beta/domains?*' => Http::response(['code' => 0, 'data' => ['results' => [['id' => 123]]]], 200),
            'https://api.sandbox.openprovider.nl/v1beta/domains/123' => Http::response(['code' => 0, 'data' => [
                'id' => 123,
                'status' => 'ACT',
                'active_date' => '2026-10-10 10:00:00',
                'registry_expiration_date' => '2027-10-10 00:00:00',
                'owner_handle' => 'NL654321-NL',
                'admin_handle' => 'NL654321-NL',
                'tech_handle' => 'NL654321-NL',
                'name_servers' => [['name' => 'ns1.openprovider.nl'], ['name' => 'ns2.openprovider.be']],
            ]], 200),
            'https://api.sandbox.openprovider.nl/v1beta/customers/NL654321-NL' => Http::response(['code' => 0, 'data' => [
                'name' => ['first_name' => 'Jan', 'last_name' => 'Jansen'],
                'address' => ['street' => 'Teststraat', 'number' => '1', 'zipcode' => '1234AB', 'city' => 'Amsterdam', 'country' => 'NL'],
                'email' => 'jan@example.test',
                'phone' => ['country_code' => '+31', 'area_code' => '6', 'subscriber_number' => '12345678'],
            ]], 200),
        ]);

        $provider = DomainProviderFactory::default();
        $info = $provider->getDomainInfo('voorbeeld.nl');
        $contacts = $provider->getContacts('voorbeeld.nl');

        $this->assertSame([['hostname' => 'ns1.openprovider.nl'], ['hostname' => 'ns2.openprovider.be']], $info['nameservers']);
        $this->assertSame('Jan', $contacts[0]['first_name']);
        $this->assertSame('registrant', $contacts[0]['type']);
        $this->assertSame('Teststraat', $contacts[0]['street']);
        $this->assertSame('+31 6 12345678', $contacts[0]['phone_number']);
    }

    public function test_registration_uses_configured_handle_and_nameservers(): void
    {
        Http::fake([
            'https://api.sandbox.openprovider.nl/v1beta/auth/login' => Http::response(['code' => 0, 'data' => ['token' => 'sandbox-token']], 200),
            'https://api.sandbox.openprovider.nl/v1beta/domains' => Http::response(['code' => 0, 'data' => ['id' => 123]], 200),
        ]);

        DomainProviderFactory::default()->registerDomain('voorbeeld.nl', [], ['ns1.openprovider.nl', 'ns2.openprovider.be']);

        Http::assertSent(fn ($request) => $request->url() === 'https://api.sandbox.openprovider.nl/v1beta/domains'
            && $request['owner_handle'] === 'XX123456-XX'
            && ! isset($request['billing_handle'])
            && $request['domain'] === ['name' => 'voorbeeld', 'extension' => 'nl']
            && $request['period'] === 1
            && $request['unit'] === 'y'
            && $request['name_servers'][0]['name'] === 'ns1.openprovider.nl');
    }
}
