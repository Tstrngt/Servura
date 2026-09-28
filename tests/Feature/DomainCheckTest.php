<?php

namespace Tests\Feature;

use App\Models\BillingSetting;
use App\Models\DomainTld;
use App\Services\Domains\DomainCheckResult;
use App\Services\Domains\DomainProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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

    public function test_domain_check_requires_valid_name(): void
    {
        $response = $this->getJson('/api/domains/check?name=voorbeeld.nl');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_domain_check_returns_unconfigured_when_provider_not_configured(): void
    {
        BillingSetting::where('key', 'transip_username')->delete();
        BillingSetting::where('key', 'transip_private_key')->delete();

        $response = $this->getJson('/api/domains/check?name=voorbeeld');

        $response->assertStatus(503);
        $response->assertJsonPath('status', 'unconfigured');
    }

    public function test_domain_check_returns_results_for_each_default_tld(): void
    {
        BillingSetting::setValue('transip_enabled', '1');
        BillingSetting::setEncryptedValue('transip_private_key', 'fake-key');
        BillingSetting::setValue('transip_username', 'testuser');
        Cache::flush();

        DomainTld::factory()->create(['extension' => '.nl', 'registration_price' => 9.99, 'is_active' => true, 'sort_order' => 1]);
        DomainTld::factory()->create(['extension' => '.com', 'registration_price' => 14.99, 'is_active' => true, 'sort_order' => 2]);
        DomainTld::factory()->create(['extension' => '.eu', 'registration_price' => 7.99, 'is_active' => true, 'sort_order' => 3]);
        DomainTld::factory()->create(['extension' => '.net', 'registration_price' => 12.99, 'is_active' => false, 'sort_order' => 4]);
        DomainTld::factory()->create(['extension' => '.be', 'registration_price' => 8.99, 'is_active' => true, 'sort_order' => 5]);

        $provider = Mockery::mock(DomainProvider::class);
        $provider->shouldReceive('isConfigured')->andReturn(true);
        $provider->shouldReceive('checkAvailability')
            ->andReturnUsing(function (string $domain) {
                $available = str_ends_with($domain, '.nl');

                return new DomainCheckResult($domain, $available, $available ? 'free' : 'notfree', '');
            });

        $this->app->instance(\App\Services\Domains\TransIpProvider::class, $provider);

        $response = $this->getJson('/api/domains/check?name=voorbeeld');

        $response->assertOk();
        $response->assertJsonPath('status', 'ok');
        $response->assertJsonPath('name', 'voorbeeld');
        $response->assertJsonCount(4, 'results');
        $response->assertJsonPath('results.0.domain', 'voorbeeld.nl');
        $response->assertJsonPath('results.0.available', true);
        $response->assertJsonPath('results.0.price', '9,99');
    }
}
