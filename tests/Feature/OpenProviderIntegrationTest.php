<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
use App\Models\User;
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
            && $request['address']['country'] === 'NL');
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
