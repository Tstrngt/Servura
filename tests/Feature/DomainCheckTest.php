<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
use App\Services\Domains\DomainCheckResult;
use App\Services\Domains\DomainProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    use RefreshDatabase;
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_domain_check_requires_valid_domain(): void
    {
        $response = $this->getJson('/api/domains/check?domain=https://voorbeeld.nl/test');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['domain']);
    }

    public function test_domain_check_returns_unconfigured_when_provider_not_configured(): void
    {
        BillingSetting::where('key', 'transip_username')->delete();
        BillingSetting::where('key', 'transip_private_key')->delete();

        $response = $this->getJson('/api/domains/check?domain=voorbeeld.nl');

        $response->assertStatus(503);
        $response->assertJsonPath('status', 'unconfigured');
    }

    public function test_domain_check_returns_available_result(): void
    {
        BillingSetting::setValue('transip_enabled', '1');
        BillingSetting::setEncryptedValue('transip_private_key', 'fake-key');
        BillingSetting::setValue('transip_username', 'testuser');
        Cache::flush();

        $provider = Mockery::mock(DomainProvider::class);
        $provider->shouldReceive('isConfigured')->once()->andReturn(true);
        $provider->shouldReceive('checkAvailability')
            ->once()
            ->with('voorbeeld.nl')
            ->andReturn(new DomainCheckResult('voorbeeld.nl', true, 'free', 'nl'));

        $this->app->instance(\App\Services\Domains\TransIpProvider::class, $provider);

        $response = $this->getJson('/api/domains/check?domain=voorbeeld.nl');

        $response->assertOk();
        $response->assertJson([
            'domain' => 'voorbeeld.nl',
            'available' => true,
            'status' => 'free',
            'tld' => 'nl',
        ]);
    }
}
