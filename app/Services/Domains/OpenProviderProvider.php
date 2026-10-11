<?php

namespace App\Services\Domains;

use App\Models\BillingSetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class OpenProviderProvider implements DomainProvider
{
    public function __construct(private readonly OpenProviderClient $client)
    {
    }

    public function name(): string
    {
        return 'Openprovider';
    }

    public function isConfigured(): bool
    {
        return $this->canAuthenticate() && filled($this->customerHandle());
    }

    public function canAuthenticate(): bool
    {
        return $this->client->configured();
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function testConnection(): array
    {
        if (! $this->client->configured()) {
            return ['success' => false, 'message' => 'Openprovider-gebruikersnaam of wachtwoord ontbreekt.'];
        }

        try {
            $this->client->clearToken();
            $this->client->request('GET', 'domains', ['limit' => 1]);

            return ['success' => true, 'message' => 'Verbinding met Openprovider geslaagd.'];
        } catch (Throwable $e) {
            Log::warning('Openprovider connection test failed', ['message' => $e->getMessage()]);

            return ['success' => false, 'message' => 'Openprovider-verbinding mislukt: '.$e->getMessage()];
        }
    }

    public function ensureCustomerHandle(User $user): string
    {
        if ($user->openprovider_handle) {
            return $user->openprovider_handle;
        }

        $parts = preg_split('/\s+/', trim($user->name), 2);
        $firstName = $parts[0] ?? 'Klant';
        $lastName = $parts[1] ?? $firstName;
        $phone = $this->phonePayload((string) $user->phone, (string) $user->country);
        $data = $this->client->request('POST', 'customers', [
            'name' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'full_name' => trim($user->name),
            ],
            'address' => [
                'street' => (string) $user->street,
                'number' => (string) $user->house_number,
                'zipcode' => (string) $user->postal_code,
                'city' => (string) $user->city,
                'country' => strtoupper((string) $user->country),
            ],
            'email' => $user->email,
            'company_name' => (string) $user->company,
            'vat' => (string) $user->vat_number,
            'phone' => $phone,
            'locale' => 'nl_NL',
            'comments' => 'Automatisch aangemaakt door Servura voor klant '.$user->id,
        ]);
        $handle = $data['handle'] ?? null;
        if (! $handle) {
            throw new RuntimeException('Openprovider heeft geen klanthandle teruggegeven.');
        }

        $user->forceFill(['openprovider_handle' => $handle])->save();

        return $handle;
    }

    public function checkAvailability(string $domain): DomainCheckResult
    {
        [$name, $extension] = $this->splitDomain($domain);
        $data = $this->client->request('POST', 'domains/check', [
            'domains' => [['name' => $name, 'extension' => $extension]],
            'with_price' => true,
        ]);
        $result = $data['results'][0] ?? [];
        $status = strtolower((string) ($result['status'] ?? 'unknown'));

        return new DomainCheckResult(
            domain: $domain,
            available: $status === 'free',
            status: $status,
            tld: $extension,
            actions: []
        );
    }

    public function suggest(string $name, array $tlds = []): array
    {
        return array_map(fn ($tld) => $this->checkAvailability($name.'.'.ltrim($tld, '.')), $tlds);
    }

    public function tlds(): array
    {
        return $this->client->request('GET', 'extensions', ['limit' => 1000])['results'] ?? [];
    }

    public function getPrice(string $extension, string $operation, int $period = 1): array
    {
        return $this->client->request('GET', 'domains/prices', [
            'domain.name' => 'servura-price-check',
            'domain.extension' => ltrim($extension, '.'),
            'operation' => $operation,
            'period' => $period,
        ]);
    }

    public function registerDomain(string $domain, array $contacts = [], array $nameservers = []): void
    {
        $this->client->request('POST', 'domains', $this->domainPayload($domain, $nameservers, $contacts));
    }

    public function transferDomain(string $domain, string $authCode, array $contacts = [], array $nameservers = []): void
    {
        $this->client->request('POST', 'domains/transfer', $this->domainPayload($domain, $nameservers, $contacts) + ['auth_code' => $authCode]);
    }

    public function cancelDomain(string $domain, string $endTime = 'end'): void
    {
        $this->client->request('DELETE', 'domains/'.$this->domainId($domain), ['type' => $endTime === 'immediately' ? 'immediate' : 'expiration']);
    }

    public function uncancelDomain(string $domain): void
    {
        $this->client->request('PUT', 'domains/'.$this->domainId($domain), ['autorenew' => 'on']);
    }

    public function getDomainInfo(string $domain): array
    {
        $data = $this->client->request('GET', 'domains/'.$this->domainId($domain));

        return [
            'status' => $data['status'] ?? null,
            'registration_date' => $data['active_date'] ?? $data['order_date'] ?? null,
            'renewal_date' => $data['expiration_date'] ?? $data['registry_expiration_date'] ?? $data['renewal_date'] ?? null,
            'auto_renew' => in_array(strtolower((string) ($data['autorenew'] ?? $data['renew'] ?? '')), ['on', '1', '2', 'true'], true),
            'nameservers' => collect($data['name_servers'] ?? [])->map(fn ($nameserver) => [
                'hostname' => $nameserver['name'] ?? null,
            ])->filter(fn ($nameserver) => filled($nameserver['hostname']))->values()->all(),
            'can_edit_dns' => true,
            'external_id' => $data['id'] ?? null,
            'raw' => $data,
        ];
    }

    public function getNameservers(string $domain): array
    {
        return collect($this->getDomainInfo($domain)['nameservers'] ?? [])->pluck('hostname')->all();
    }

    public function setNameservers(string $domain, array $nameservers): void
    {
        if (BillingSetting::valueFor('openprovider_environment', 'sandbox') === 'sandbox') {
            Log::info('Openprovider sandbox nameserver update simulated', [
                'domain' => $domain,
                'nameservers' => $this->nameserverPayload($nameservers),
            ]);

            return;
        }

        $this->client->request('PUT', 'domains/'.$this->domainId($domain), [
            'name_servers' => $this->nameserverPayload($nameservers),
        ]);
    }

    public function getContacts(string $domain): array
    {
        $raw = $this->getDomainInfo($domain)['raw'] ?? [];

        return collect([
            'registrant' => $raw['owner_handle'] ?? null,
            'administrative' => $raw['admin_handle'] ?? null,
            'technical' => $raw['tech_handle'] ?? null,
        ])->filter()->map(function ($handle, $type) {
            $customer = $this->client->request('GET', 'customers/'.$handle);
            $name = $customer['name'] ?? [];
            $address = $customer['address'] ?? [];
            $phone = $customer['phone'] ?? [];

            return [
                'type' => $type,
                'handle' => $handle,
                'first_name' => $name['first_name'] ?? '',
                'last_name' => $name['last_name'] ?? '',
                'company_name' => $customer['company_name'] ?? '',
                'company_kvk' => data_get($customer, 'additional_data.company_registration_number', ''),
                'street' => $address['street'] ?? '',
                'number' => trim(($address['number'] ?? '').' '.($address['suffix'] ?? '')),
                'postal_code' => $address['zipcode'] ?? '',
                'city' => $address['city'] ?? '',
                'country' => $address['country'] ?? '',
                'email' => $customer['email'] ?? '',
                'phone_number' => trim(($phone['country_code'] ?? '').' '.($phone['area_code'] ?? '').' '.($phone['subscriber_number'] ?? '')),
            ];
        })->values()->all();
    }

    public function setContacts(string $domain, array $contacts): void
    {
        $raw = $this->getDomainInfo($domain)['raw'] ?? [];
        $handleKeys = [
            'registrant' => 'owner_handle',
            'administrative' => 'admin_handle',
            'technical' => 'tech_handle',
        ];

        foreach ($contacts as $contact) {
            $handle = $raw[$handleKeys[$contact['type'] ?? ''] ?? ''] ?? null;
            if (! $handle) {
                continue;
            }

            $name = trim(($contact['first_name'] ?? '').' '.($contact['last_name'] ?? ''));
            $this->client->request('PUT', 'customers/'.$handle, [
                'name' => [
                    'first_name' => $contact['first_name'] ?? '',
                    'last_name' => $contact['last_name'] ?? '',
                    'full_name' => $name,
                ],
                'address' => [
                    'street' => $contact['street'] ?? '',
                    'number' => $contact['number'] ?? '',
                    'zipcode' => $contact['postal_code'] ?? '',
                    'city' => $contact['city'] ?? '',
                    'country' => strtoupper($contact['country'] ?? ''),
                ],
                'email' => $contact['email'] ?? '',
                'company_name' => $contact['company_name'] ?? '',
                'phone' => $this->phonePayload($contact['phone_number'] ?? '', $contact['country'] ?? ''),
            ]);
        }
    }

    public function getDnsEntries(string $domain): array
    {
        $data = $this->client->request('GET', 'dns/zones/'.$domain.'/records', ['limit' => 1000]);

        return collect($data['results'] ?? $data['records'] ?? [])->map(fn ($record) => [
            'name' => $record['name'] ?? '@',
            'type' => strtoupper($record['type'] ?? 'A'),
            'content' => $record['value'] ?? $record['content'] ?? '',
            'expire' => (int) ($record['ttl'] ?? 3600),
            'priority' => $record['priority'] ?? null,
        ])->all();
    }

    public function setDnsEntries(string $domain, array $dnsEntries): void
    {
        $records = collect($dnsEntries)->map(fn ($record) => array_filter([
            'name' => $record['name'] ?? '@',
            'type' => strtoupper($record['type'] ?? 'A'),
            'value' => $record['content'] ?? $record['value'] ?? '',
            'ttl' => (int) ($record['expire'] ?? $record['ttl'] ?? 3600),
            'priority' => $record['priority'] ?? null,
        ], fn ($value) => $value !== null))->values()->all();

        $this->client->request('PUT', 'dns/zones/'.$domain.'/records', ['records' => $records]);
    }

    public function getAuthCode(string $domain): ?string
    {
        $data = $this->client->request('GET', 'domains/'.$this->domainId($domain).'/authcode');

        return $data['auth_code'] ?? null;
    }

    public function requestAuthCode(string $domain): void
    {
        $this->client->request('POST', 'domains/'.$this->domainId($domain).'/authcode/reset', ['auth_code_type' => 'external']);
    }

    public function getTldCapabilities(string $tld): array
    {
        $data = $this->client->request('GET', 'extensions', ['name_pattern' => ltrim($tld, '.'), 'limit' => 1]);

        $extension = $data['results'][0] ?? [];

        return $extension + [
            'can_set_nameservers' => true,
            'can_set_contacts' => true,
        ];
    }

    private function domainPayload(string $domain, array $nameservers, array $contacts = []): array
    {
        [$name, $extension] = $this->splitDomain($domain);
        $handle = $contacts[0]['handle'] ?? $this->customerHandle();

        if (! $handle) {
            throw new RuntimeException('Openprovider-klanthandle ontbreekt.');
        }

        return [
            'domain' => ['name' => $name, 'extension' => $extension],
            'owner_handle' => $handle,
            'admin_handle' => $handle,
            'tech_handle' => $handle,
            'autorenew' => 'on',
            'period' => 1,
            'unit' => 'y',
            'name_servers' => $this->nameserverPayload($nameservers),
        ];
    }

    private function domainId(string $domain): int
    {
        $data = $this->client->request('GET', 'domains', ['full_name' => strtolower($domain), 'limit' => 1]);
        $result = $data['results'][0] ?? null;

        if (! $result || empty($result['id'])) {
            throw new RuntimeException('Domein niet gevonden in Openprovider: '.$domain);
        }

        return (int) $result['id'];
    }

    private function splitDomain(string $domain): array
    {
        $parts = explode('.', strtolower(trim($domain)), 2);
        if (count($parts) !== 2) {
            throw new RuntimeException('Ongeldige domeinnaam: '.$domain);
        }

        return $parts;
    }

    private function nameserverPayload(array $nameservers): array
    {
        return collect($nameservers)->map(function ($nameserver, $index) {
            $name = is_array($nameserver) ? ($nameserver['name'] ?? $nameserver['hostname'] ?? null) : (method_exists($nameserver, 'getHostname') ? $nameserver->getHostname() : (string) $nameserver);

            return ['name' => $name, 'seq_nr' => $index + 1];
        })->filter(fn ($nameserver) => filled($nameserver['name']))->values()->all();
    }

    private function phonePayload(string $number, string $country): array
    {
        $digits = preg_replace('/\D+/', '', $number);
        if ($digits === '') {
            throw new RuntimeException('Een telefoonnummer is verplicht om de klant bij Openprovider aan te maken.');
        }

        if (strtoupper($country) === 'NL') {
            if (str_starts_with($digits, '0031')) {
                $digits = substr($digits, 4);
            } elseif (str_starts_with($digits, '31')) {
                $digits = substr($digits, 2);
            }
            $digits = ltrim($digits, '0');
            $areaLength = str_starts_with($digits, '6') ? 1 : 2;

            return [
                'country_code' => '+31',
                'area_code' => substr($digits, 0, $areaLength),
                'subscriber_number' => substr($digits, $areaLength),
            ];
        }

        throw new RuntimeException('Gebruik voor Openprovider een Nederlands telefoonnummer met landcode.');
    }

    private function customerHandle(): string
    {
        return BillingSetting::valueFor('openprovider_customer_handle', '');
    }
}
