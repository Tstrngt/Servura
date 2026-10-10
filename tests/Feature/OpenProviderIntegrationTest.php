<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
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

    public function test_registration_uses_configured_handle_and_nameservers(): void
    {
        Http::fake([
            'https://api.sandbox.openprovider.nl/v1beta/auth/login' => Http::response(['code' => 0, 'data' => ['token' => 'sandbox-token']], 200),
            'https://api.sandbox.openprovider.nl/v1beta/domains' => Http::response(['code' => 0, 'data' => ['id' => 123]], 200),
        ]);

        DomainProviderFactory::default()->registerDomain('voorbeeld.nl', [], ['ns1.openprovider.nl', 'ns2.openprovider.be']);

        Http::assertSent(fn ($request) => $request->url() === 'https://api.sandbox.openprovider.nl/v1beta/domains'
            && $request['owner_handle'] === 'XX123456-XX'
            && $request['domain'] === ['name' => 'voorbeeld', 'extension' => 'nl']
            && $request['name_servers'][0]['name'] === 'ns1.openprovider.nl');
    }
}
