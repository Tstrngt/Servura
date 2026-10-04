<?php

namespace App\Services\Domains;

use App\Services\DomainMockData;
use DateTimeImmutable;

/**
 * Mock provider for the demo domain. It implements the same interface as
 * TransIpProvider so the frontend does not need demo-specific code.
 */
class DemoDomainProvider implements DomainProvider
{
    public function name(): string
    {
        return 'demo';
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function testConnection(): array
    {
        return ['success' => true, 'message' => 'Demo provider is actief.'];
    }

    public function checkAvailability(string $domain): DomainCheckResult
    {
        return new DomainCheckResult(
            domain: $domain,
            available: false,
            status: 'unavailable',
            tld: null,
            actions: []
        );
    }

    public function suggest(string $name, array $tlds = []): array
    {
        return [];
    }

    public function tlds(): array
    {
        return [];
    }

    public function registerDomain(string $domain, array $contacts = [], array $nameservers = []): void
    {
        // No-op in demo mode.
    }

    public function transferDomain(string $domain, string $authCode, array $contacts = [], array $nameservers = []): void
    {
        // No-op in demo mode.
    }

    public function cancelDomain(string $domain, string $endTime = 'end'): void
    {
        // No-op in demo mode.
    }

    public function uncancelDomain(string $domain): void
    {
        // No-op in demo mode.
    }

    public function getDomainInfo(string $domain): array
    {
        $overview = DomainMockData::overview($domain);

        return [
            'name' => $overview['domain_name'],
            'status' => 'active',
            'registration_date' => (new DateTimeImmutable())->modify('-1 year')->format('Y-m-d'),
            'renewal_date' => (new DateTimeImmutable())->modify('+1 year')->format('Y-m-d'),
            'cancellation_date' => null,
            'cancellation_status' => null,
            'is_transfer_locked' => $overview['registrar_lock'],
            'has_auto_dns' => true,
            'is_dns_only' => false,
            'can_edit_dns' => true,
            'has_dns_sec' => false,
            'nameservers' => DomainMockData::nameservers(),
            'contacts' => [],
        ];
    }

    public function getNameservers(string $domain): array
    {
        return DomainMockData::nameservers();
    }

    public function setNameservers(string $domain, array $nameservers): void
    {
        // No-op in demo mode.
    }

    public function getContacts(string $domain): array
    {
        $holder = DomainMockData::holder();

        return [
            [
                'type' => 'registrant',
                'first_name' => $holder['first_name'],
                'last_name' => $holder['last_name'],
                'company_name' => $holder['company_name'],
                'company_kvk' => '',
                'company_type' => '',
                'street' => $holder['street'],
                'number' => $holder['number'],
                'postal_code' => $holder['postal_code'],
                'city' => $holder['city'],
                'phone_number' => $holder['phone_number'],
                'email' => $holder['email'],
                'country' => $holder['country'],
            ],
        ];
    }

    public function setContacts(string $domain, array $contacts): void
    {
        // No-op in demo mode.
    }

    public function getDnsEntries(string $domain): array
    {
        return DomainMockData::dnsRecords();
    }

    public function setDnsEntries(string $domain, array $dnsEntries): void
    {
        // No-op in demo mode.
    }

    public function getAuthCode(string $domain): ?string
    {
        return DomainMockData::transferToken()['token'];
    }

    public function requestAuthCode(string $domain): void
    {
        // No-op in demo mode.
    }

    public function getTldCapabilities(string $tld): array
    {
        return [
            'name' => ltrim(strtolower($tld), '.'),
            'capabilities' => [
                \Transip\Api\Library\Entity\Tld::CAPABILITY_CANSETNAMESERVERS,
                \Transip\Api\Library\Entity\Tld::CAPABILITY_CANSETCONTACTS,
            ],
            'price' => 0,
            'recurring_price' => 0,
            'min_length' => 2,
            'max_length' => 63,
            'registration_period_length' => 12,
            'cancel_time_frame' => 0,
        ];
    }
}
