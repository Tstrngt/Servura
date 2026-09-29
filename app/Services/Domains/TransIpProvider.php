<?php

namespace App\Services\Domains;

use App\Models\BillingSetting;
use Illuminate\Support\Facades\Log;
use Throwable;
use Transip\Api\Library\Entity\Domain\DnsEntry;
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
        return false;
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
                'exception' => get_class($e),
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

        if ($this->isTestDomain($domain)) {
            return new DomainCheckResult(
                domain: $domain,
                available: true,
                status: 'free',
                tld: $tld,
                actions: [],
            );
        }

        try {
            $result = $this->client()->domainAvailability()->checkDomainName($domain);

            return $this->mapCheckResult($result, $domain, $tld);
        } catch (Throwable $e) {
            Log::warning('TransIP domain availability check failed', [
                'domain' => $domain,
                'exception' => get_class($e),
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

    public function registerDomain(string $domain, array $contacts = [], array $nameservers = []): void
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            Log::info('TransIP dummy domain registration simulated.', ['domain' => $domain]);

            return;
        }

        $this->client()->domain()->register($domain, $contacts, $nameservers);
    }

    public function transferDomain(string $domain, string $authCode, array $contacts = [], array $nameservers = []): void
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            Log::info('TransIP dummy domain transfer simulated.', ['domain' => $domain]);

            return;
        }

        $this->client()->domain()->transfer($domain, $authCode, $contacts, $nameservers);
    }

    public function getDomainInfo(string $domain): array
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            return [
                'name' => $domain,
                'status' => 'active',
                'registration_date' => now()->subYear()->toDateString(),
                'renewal_date' => now()->addYear()->toDateString(),
                'cancellation_date' => null,
                'cancellation_status' => null,
                'is_transfer_locked' => false,
                'has_auto_dns' => true,
                'is_dns_only' => false,
                'can_edit_dns' => true,
                'has_dns_sec' => false,
                'nameservers' => $this->buildDefaultNameserverArray(),
                'contacts' => [],
            ];
        }

        try {
            $info = $this->client()->domain()->getByName($domain, ['nameservers', 'contacts']);

            return [
                'name' => $info->getName(),
                'status' => $info->getStatus(),
                'registration_date' => $info->getRegistrationDate(),
                'renewal_date' => $info->getRenewalDate(),
                'cancellation_date' => $info->getCancellationDate(),
                'cancellation_status' => $info->getCancellationStatus(),
                'is_transfer_locked' => $info->isTransferLocked(),
                'has_auto_dns' => method_exists($info, 'isHasAutoDns') ? $info->isHasAutoDns() : false,
                'is_dns_only' => $info->isDnsOnly(),
                'can_edit_dns' => $info->isCanEditDns(),
                'has_dns_sec' => $info->isHasDnsSec(),
                'nameservers' => array_map(fn ($ns) => [
                    'hostname' => $ns->getHostname(),
                    'ipv4' => $ns->getIpv4(),
                    'ipv6' => $ns->getIpv6(),
                ], $info->getNameservers()),
                'contacts' => array_map(fn ($c) => [
                    'type' => $c->getType(),
                    'first_name' => $c->getFirstName(),
                    'last_name' => $c->getLastName(),
                    'company_name' => $c->getCompanyName(),
                    'company_kvk' => $c->getCompanyKvk(),
                    'company_type' => $c->getCompanyType(),
                    'street' => $c->getStreet(),
                    'number' => $c->getNumber(),
                    'postal_code' => $c->getPostalCode(),
                    'city' => $c->getCity(),
                    'phone_number' => $c->getPhoneNumber(),
                    'email' => $c->getEmail(),
                    'country' => $c->getCountry(),
                ], $info->getContacts()),
            ];
        } catch (Throwable $e) {
            Log::warning('TransIP getDomainInfo failed', [
                'domain' => $domain,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function getNameservers(string $domain): array
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            return $this->buildDefaultNameserverArray();
        }

        $nameservers = $this->client()->domainNameserver()->getByDomainName($domain);

        return array_map(fn ($ns) => [
            'hostname' => $ns->getHostname(),
            'ipv4' => $ns->getIpv4(),
            'ipv6' => $ns->getIpv6(),
        ], $nameservers);
    }

    public function setNameservers(string $domain, array $nameservers): void
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            Log::info('TransIP dummy domain nameservers updated.', ['domain' => $domain, 'nameservers' => $nameservers]);

            return;
        }

        $entities = array_map(function ($ns) {
            $entity = new Nameserver();
            $entity->setHostname($ns['hostname'] ?? '');
            if (isset($ns['ipv4']) && $ns['ipv4'] !== '') {
                $entity->setIpv4($ns['ipv4']);
            }
            if (isset($ns['ipv6']) && $ns['ipv6'] !== '') {
                $entity->setIpv6($ns['ipv6']);
            }

            return $entity;
        }, $nameservers);

        $this->client()->domainNameserver()->update($domain, $entities);
    }

    public function getContacts(string $domain): array
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            return [];
        }

        $contacts = $this->client()->domainContact()->getByDomainName($domain);

        return array_map(fn ($c) => [
            'type' => $c->getType(),
            'first_name' => $c->getFirstName(),
            'last_name' => $c->getLastName(),
            'company_name' => $c->getCompanyName(),
            'company_kvk' => $c->getCompanyKvk(),
            'company_type' => $c->getCompanyType(),
            'street' => $c->getStreet(),
            'number' => $c->getNumber(),
            'postal_code' => $c->getPostalCode(),
            'city' => $c->getCity(),
            'phone_number' => $c->getPhoneNumber(),
            'email' => $c->getEmail(),
            'country' => $c->getCountry(),
        ], $contacts);
    }

    public function setContacts(string $domain, array $contacts): void
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            Log::info('TransIP dummy domain contacts updated.', ['domain' => $domain]);

            return;
        }

        $entities = array_map(function ($c) {
            $contact = new WhoisContact();
            $contact->setType($c['type'] ?? WhoisContact::CONTACT_TYPE_REGISTRANT);
            $contact->setFirstName($c['first_name'] ?? '');
            $contact->setLastName($c['last_name'] ?? '');
            $contact->setCompanyName($c['company_name'] ?? '');
            $contact->setCompanyKvk($c['company_kvk'] ?? '');
            $contact->setCompanyType($c['company_type'] ?? '');
            $contact->setStreet($c['street'] ?? '');
            $contact->setNumber($c['number'] ?? '');
            $contact->setPostalCode($c['postal_code'] ?? '');
            $contact->setCity($c['city'] ?? '');
            $contact->setPhoneNumber($c['phone_number'] ?? '');
            $contact->setEmail($c['email'] ?? '');
            $contact->setCountry(strtoupper($c['country'] ?? ''));

            return $contact;
        }, $contacts);

        $this->client()->domainContact()->update($domain, $entities);
    }

    public function getDnsEntries(string $domain): array
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            return [];
        }

        $entries = $this->client()->domainDns()->getByDomainName($domain);

        return array_map(fn ($entry) => [
            'name' => $entry->getName(),
            'type' => $entry->getType(),
            'expire' => $entry->getExpire(),
            'content' => $entry->getContent(),
        ], $entries);
    }

    public function setDnsEntries(string $domain, array $dnsEntries): void
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            Log::info('TransIP dummy domain DNS entries updated.', ['domain' => $domain]);

            return;
        }

        $entities = array_map(function ($entry) {
            $entity = new DnsEntry();
            $entity->setName($entry['name'] ?? '');
            $entity->setType($entry['type'] ?? 'A');
            $entity->setExpire((int) ($entry['expire'] ?? 3600));
            $entity->setContent($entry['content'] ?? '');

            return $entity;
        }, $dnsEntries);

        $this->client()->domainDns()->update($domain, $entities);
    }

    public function getAuthCode(string $domain): ?string
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            return null;
        }

        try {
            return $this->client()->domainAuthCode()->getByDomainName($domain);
        } catch (Throwable $e) {
            Log::warning('TransIP getAuthCode failed', [
                'domain' => $domain,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function requestAuthCode(string $domain): void
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->isTestDomain($domain)) {
            Log::info('TransIP dummy domain authcode requested.', ['domain' => $domain]);

            return;
        }

        $this->client()->domainAuthCode()->requestForDomainName($domain);
    }

    public function getTldCapabilities(string $tld): array
    {
        $tld = ltrim(strtolower($tld), '.');

        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $info = $this->client()->domainTlds()->getByTld($tld);

            return [
                'name' => $info->getName(),
                'capabilities' => $info->getCapabilities(),
                'price' => $info->getPrice() / 100,
                'recurring_price' => $info->getRecurringPrice() / 100,
                'min_length' => $info->getMinLength(),
                'max_length' => $info->getMaxLength(),
                'registration_period_length' => $info->getRegistrationPeriodLength(),
                'cancel_time_frame' => $info->getCancelTimeFrame(),
            ];
        } catch (Throwable $e) {
            Log::warning('TransIP getTldCapabilities failed', [
                'tld' => $tld,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function suggest(string $name, array $tlds = []): array
    {
        $name = $this->normalizeDomainName($name);
        $tlds = $tlds === [] ? config('domains.default_tlds', ['nl']) : $tlds;

        $results = [];
        foreach ($tlds as $tld) {
            $results[] = $this->checkAvailability($name.'.'.ltrim($tld, '.'));
        }

        return $results;
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

    /**
     * @return array<int, array<string, string|null>>
     */
    private function buildDefaultNameserverArray(): array
    {
        try {
            $raw = BillingSetting::valueFor('transip_default_nameservers', '');
        } catch (\Throwable $e) {
            $raw = '';
        }

        $hosts = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $raw))));

        return array_map(fn ($host) => ['hostname' => $host, 'ipv4' => null, 'ipv6' => null], $hosts);
    }

    private function testDomains(): array
    {
        try {
            $raw = BillingSetting::valueFor('transip_test_domains', '');
        } catch (\Throwable $e) {
            // BillingSetting is not available in isolated unit tests.
            return [];
        }

        if ($raw === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', $raw))));
    }

    private function isTestDomain(string $domain): bool
    {
        return in_array($this->normalizeDomain($domain), $this->testDomains(), true);
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = mb_strtolower(trim($domain));
        $domain = preg_replace('#^https?://#', '', $domain) ?? $domain;
        $domain = preg_replace('#^(www\.)?([^/]+).*#', '$2', $domain) ?? $domain;

        return $domain;
    }

    private function normalizeDomainName(string $name): string
    {
        $name = mb_strtolower(trim($name));
        $name = preg_replace('#^https?://#', '', $name) ?? $name;
        $name = preg_replace('#^(www\.)?([^/]+).*#', '$2', $name) ?? $name;
        $name = preg_replace('/\.\w+$/', '', $name) ?? $name;

        return $name;
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
