<?php

namespace App\Services\Domains;

use Illuminate\Support\Facades\Log;
use Throwable;
use Transip\Api\Library\Entity\DomainCheckResult as TransipDomainCheckResult;
use Transip\Api\Library\Exception\ApiException;
use Transip\Api\Library\Exception\HttpBadResponseException;
use Transip\Api\Library\Exception\HttpRequestException;
use Transip\Api\Library\TransipAPI;

class TransIpProvider implements DomainProvider
{
    private ?TransipAPI $client = null;

    public function __construct(private readonly TransIpService $service)
    {
    }

    public function name(): string
    {
        return 'TransIP';
    }

    public function isConfigured(): bool
    {
        return $this->service->isConfigured();
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function testConnection(): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'TransIP gebruikersnaam of private key ontbreekt.',
            ];
        }

        try {
            $success = $this->client()->test()->test() === true;

            return [
                'success' => $success,
                'message' => $success ? 'Verbinding met TransIP geslaagd.' : 'TransIP verbinding mislukt.',
            ];
        } catch (ApiException|HttpRequestException|HttpBadResponseException $e) {
            Log::warning('TransIP connection test failed', [
                'message' => $e->getMessage(),
            ]);

            $detail = $e->getMessage();

            return [
                'success' => false,
                'message' => 'Verbinding mislukt'.($detail !== '' ? ': '.$detail : '. Controleer je inloggegevens en whitelist-instellingen.'),
            ];
        } catch (Throwable $e) {
            Log::warning('TransIP connection test failed', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Verbinding mislukt. Controleer je inloggegevens en whitelist-instellingen.',
            ];
        }
    }

    public function checkAvailability(string $domain): DomainCheckResult
    {
        $domain = $this->normalizeDomain($domain);
        $tld = $this->extractTld($domain);

        try {
            $result = $this->client()->domainAvailability()->checkDomainName($domain);

            return $this->mapCheckResult($result, $domain, $tld);
        } catch (Throwable $e) {
            Log::warning('TransIP domain availability check failed', [
                'domain' => $domain,
                'message' => $e->getMessage(),
            ]);

            return new DomainCheckResult(
                domain: $domain,
                available: false,
                status: 'error',
                tld: $tld,
            );
        }
    }

    public function tlds(): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $tlds = $this->client()->domainTlds()->getAll();

            return array_map(fn ($tld) => [
                'name' => $tld->getName(),
                'price' => $tld->getPrice() / 100, // API returns cents; convert to euros
                'recurring_price' => $tld->getRecurringPrice() / 100,
                'registration_period_length' => $tld->getRegistrationPeriodLength(),
                'capabilities' => $tld->getCapabilities(),
                'min_length' => $tld->getMinLength(),
                'max_length' => $tld->getMaxLength(),
            ], $tlds);
        } catch (Throwable $e) {
            Log::warning('TransIP TLD list failed', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    private function client(): TransipAPI
    {
        return $this->client ??= $this->service->client();
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = mb_strtolower(trim($domain));
        $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
        $domain = preg_replace('#^(www\.)?([^/]+).*#', '$2', $domain) ?? $domain;

        return $domain;
    }

    private function extractTld(string $domain): ?string
    {
        $parts = explode('.', $domain, 2);

        return $parts[1] ?? null;
    }

    private function mapCheckResult(TransipDomainCheckResult $result, string $domain, ?string $tld): DomainCheckResult
    {
        $status = $result->getStatus();
        $available = $status === TransipDomainCheckResult::STATUS_FREE;

        return new DomainCheckResult(
            domain: $domain,
            available: $available,
            status: $status,
            tld: $tld,
            actions: $result->getActions(),
        );
    }
}
