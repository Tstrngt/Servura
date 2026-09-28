<?php

namespace Tests\Unit\Services\Domains;

use App\Services\Domains\TransIpProvider;
use App\Services\Domains\TransIpService;
use Mockery;
use PHPUnit\Framework\TestCase;
use Transip\Api\Library\Entity\DomainCheckResult;
use Transip\Api\Library\Repository\DomainAvailabilityRepository;
use Transip\Api\Library\TransipAPI;

class TransIpProviderTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_normalize_domain_strips_protocol_and_path(): void
    {
        $service = Mockery::mock(TransIpService::class);
        $service->shouldReceive('isConfigured')->andReturn(true);

        $availabilityRepo = Mockery::mock(DomainAvailabilityRepository::class);
        $availabilityRepo->shouldReceive('checkDomainName')
            ->with('voorbeeld.nl')
            ->andReturn(new DomainCheckResult([
                'domainName' => 'voorbeeld.nl',
                'status' => DomainCheckResult::STATUS_FREE,
                'actions' => [],
            ]));

        $api = Mockery::mock(TransipAPI::class);
        $api->shouldReceive('setReadOnlyMode')->with(true);
        $api->shouldReceive('domainAvailability')->andReturn($availabilityRepo);

        $service->shouldReceive('client')->andReturn($api);

        $provider = new TransIpProvider($service);
        $result = $provider->checkAvailability('https://www.voorbeeld.nl/test');

        $this->assertTrue($result->available);
        $this->assertSame('free', $result->status);
        $this->assertSame('voorbeeld.nl', $result->domain);
    }

    public function test_unavailable_domain_returns_not_available(): void
    {
        $service = Mockery::mock(TransIpService::class);
        $service->shouldReceive('isConfigured')->andReturn(true);

        $availabilityRepo = Mockery::mock(DomainAvailabilityRepository::class);
        $availabilityRepo->shouldReceive('checkDomainName')
            ->with('bezet.nl')
            ->andReturn(new DomainCheckResult([
                'domainName' => 'bezet.nl',
                'status' => DomainCheckResult::STATUS_UNAVAILABLE,
                'actions' => [],
            ]));

        $api = Mockery::mock(TransipAPI::class);
        $api->shouldReceive('setReadOnlyMode')->with(true);
        $api->shouldReceive('domainAvailability')->andReturn($availabilityRepo);

        $service->shouldReceive('client')->andReturn($api);

        $provider = new TransIpProvider($service);
        $result = $provider->checkAvailability('bezet.nl');

        $this->assertFalse($result->available);
        $this->assertSame('unavailable', $result->status);
    }
}
