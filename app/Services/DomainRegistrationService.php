<?php

namespace App\Services;

use App\Models\BillingSetting;
use App\Models\CustomerService;
use App\Models\DomainRegistration;
use App\Models\DomainTld;
use App\Services\Domains\DomainProviderFactory;
use App\Services\Domains\OpenProviderProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;
use Transip\Api\Library\Entity\Domain\Nameserver;
use Transip\Api\Library\Entity\Domain\WhoisContact;

class DomainRegistrationService
{
    public function register(CustomerService $customerService): DomainRegistration
    {
        $domainRegistration = DomainRegistration::firstOrCreate(
            [
                'customer_service_id' => $customerService->id,
                'type' => DomainRegistration::TYPE_REGISTRATION,
            ],
            [
                'user_id' => $customerService->user_id,
                'order_id' => $customerService->order?->id,
                'domain_name' => $customerService->domain,
                'tld' => $this->extractTld($customerService->domain),
                'status' => DomainRegistration::STATUS_AWAITING_PAYMENT,
                'provider' => BillingSetting::valueFor('domain_provider', 'transip'),
                'registration_price' => $customerService->price,
                'renewal_price' => $this->resolveRenewalPrice($customerService),
                'auto_renew' => true,
                'type' => DomainRegistration::TYPE_REGISTRATION,
            ]
        );

        if (in_array($domainRegistration->status, [DomainRegistration::STATUS_ACTIVE, DomainRegistration::STATUS_REGISTERING], true)) {
            return $domainRegistration;
        }

        $domainRegistration->update(['status' => DomainRegistration::STATUS_REGISTERING]);

        $validation = $this->validateCustomerAndProvider($domainRegistration, $customerService);
        if ($validation !== null) {
            return $validation;
        }

        [$provider, $user] = $this->prepareProvider($domainRegistration, $customerService);

        try {
            $availability = $provider->checkAvailability($customerService->domain);
            if (! $availability->available) {
                return $this->fail($domainRegistration, 'Domein is inmiddels niet meer beschikbaar: '.$availability->status, DomainRegistration::TYPE_REGISTRATION);
            }

            $contact = $provider instanceof OpenProviderProvider
                ? ['handle' => $provider->ensureCustomerHandle($user)]
                : $this->buildRegistrantContact($user);
            $nameservers = $this->buildNameservers();

            $provider->registerDomain($customerService->domain, [$contact], $nameservers);

            DB::transaction(function () use ($domainRegistration, $customerService) {
                $domainRegistration->update([
                    'status' => DomainRegistration::STATUS_ACTIVE,
                    'registered_at' => now(),
                    'expires_at' => now()->addYear(),
                ]);

                $this->activateCustomerService($customerService);
            });

            Log::info('Domain registration successful', [
                'domain' => $customerService->domain,
                'customer_service_id' => $customerService->id,
                'user_id' => $user->id,
                'provider' => BillingSetting::valueFor('domain_provider', 'transip'),
            ]);

            return $domainRegistration;
        } catch (Throwable $e) {
            Log::warning('Domain registration failed', [
                'domain' => $customerService->domain,
                'customer_service_id' => $customerService->id,
                'user_id' => $user->id,
                'provider' => BillingSetting::valueFor('domain_provider', 'transip'),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return $this->fail($domainRegistration, 'Domeinproviderfout: '.$e->getMessage(), DomainRegistration::TYPE_REGISTRATION);
        }
    }

    public function transfer(CustomerService $customerService): DomainRegistration
    {
        $domainRegistration = DomainRegistration::firstOrCreate(
            [
                'customer_service_id' => $customerService->id,
                'type' => DomainRegistration::TYPE_TRANSFER,
            ],
            [
                'user_id' => $customerService->user_id,
                'order_id' => $customerService->order?->id,
                'domain_name' => $customerService->domain,
                'tld' => $this->extractTld($customerService->domain),
                'status' => DomainRegistration::STATUS_TRANSFER_PENDING,
                'provider' => BillingSetting::valueFor('domain_provider', 'transip'),
                'transfer_price' => $customerService->price,
                'auto_renew' => true,
                'type' => DomainRegistration::TYPE_TRANSFER,
            ]
        );

        if (in_array($domainRegistration->status, [DomainRegistration::STATUS_TRANSFER_ACTIVE, DomainRegistration::STATUS_TRANSFER_PROCESSING], true)) {
            return $domainRegistration;
        }

        $domainRegistration->update(['status' => DomainRegistration::STATUS_TRANSFER_PROCESSING]);

        $validation = $this->validateCustomerAndProvider($domainRegistration, $customerService);
        if ($validation !== null) {
            return $validation;
        }

        [$provider, $user] = $this->prepareProvider($domainRegistration, $customerService);

        $authCode = $domainRegistration->auth_code;
        if (empty($authCode)) {
            return $this->fail($domainRegistration, 'Geen verhuiscode beschikbaar.', DomainRegistration::TYPE_TRANSFER);
        }

        try {
            $contact = $provider instanceof OpenProviderProvider
                ? ['handle' => $provider->ensureCustomerHandle($user)]
                : $this->buildRegistrantContact($user);
            $nameservers = $this->buildNameservers();

            $provider->transferDomain($customerService->domain, $authCode, [$contact], $nameservers);

            DB::transaction(function () use ($domainRegistration, $customerService) {
                $domainRegistration->update([
                    'status' => DomainRegistration::STATUS_TRANSFER_ACTIVE,
                    'registered_at' => now(),
                    'expires_at' => now()->addYear(),
                    'auth_code' => null,
                ]);

                $this->activateCustomerService($customerService);
            });

            Log::info('Domain transfer started', [
                'domain' => $customerService->domain,
                'customer_service_id' => $customerService->id,
                'user_id' => $user->id,
                'provider' => BillingSetting::valueFor('domain_provider', 'transip'),
            ]);

            return $domainRegistration;
        } catch (Throwable $e) {
            Log::warning('Domain transfer failed', [
                'domain' => $customerService->domain,
                'customer_service_id' => $customerService->id,
                'user_id' => $user->id,
                'provider' => BillingSetting::valueFor('domain_provider', 'transip'),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return $this->fail($domainRegistration, 'Domeinproviderfout: '.$e->getMessage(), DomainRegistration::TYPE_TRANSFER);
        }
    }

    private function fail(DomainRegistration $domainRegistration, string $message, string $type = DomainRegistration::TYPE_REGISTRATION): DomainRegistration
    {
        $status = $type === DomainRegistration::TYPE_TRANSFER
            ? DomainRegistration::STATUS_TRANSFER_FAILED
            : DomainRegistration::STATUS_REGISTRATION_FAILED;

        $domainRegistration->update([
            'status' => $status,
            'error_message' => $message,
        ]);

        $customerService = $domainRegistration->customerService;
        if ($customerService) {
            $customerService->update([
                'status' => 'suspended',
                'suspension_reason' => $type === DomainRegistration::TYPE_TRANSFER ? 'transfer_failed' : 'registration_failed',
                'provisioning_status' => 'failed',
                'provisioning_error' => $message,
            ]);
        }

        Log::info('Domain operation failed status set', [
            'domain' => $domainRegistration->domain_name,
            'type' => $type,
            'customer_service_id' => $customerService?->id,
            'message' => $message,
        ]);

        return $domainRegistration;
    }

    private function validateCustomerAndProvider(DomainRegistration $domainRegistration, CustomerService $customerService): ?DomainRegistration
    {
        $provider = DomainProviderFactory::default();
        if (! $provider || ! $provider->isConfigured()) {
            return $this->fail($domainRegistration, 'Domeinprovider is niet geconfigureerd.', $domainRegistration->type);
        }

        $user = $customerService->user;
        $missing = array_filter([
            'naam' => $user->name,
            'straat' => $user->street,
            'huisnummer' => $user->house_number,
            'postcode' => $user->postal_code,
            'plaats' => $user->city,
            'land' => $user->country,
            'e-mail' => $user->email,
        ], fn ($value) => blank($value));

        if (! empty($missing)) {
            return $this->fail($domainRegistration, 'Ontbrekende klantgegevens: '.implode(', ', array_keys($missing)).'.', $domainRegistration->type);
        }

        return null;
    }

    private function prepareProvider(DomainRegistration $domainRegistration, CustomerService $customerService): array
    {
        return [DomainProviderFactory::default(), $customerService->user];
    }

    private function activateCustomerService(CustomerService $customerService): void
    {
        $customerService->update([
            'status' => 'active',
            'suspension_reason' => null,
            'suspended_at' => null,
            'provisioning_status' => 'active',
            'provisioning_error' => null,
            'provisioned_at' => now(),
            'current_period_start' => now(),
            'current_period_end' => now()->addYear(),
            'next_invoice_date' => now()->addYear()->subDays(14),
        ]);
    }

    private function extractTld(?string $domain): string
    {
        if (! $domain) {
            return '';
        }

        $parts = explode('.', $domain, 2);

        return isset($parts[1]) ? '.'.$parts[1] : $domain;
    }

    private function resolveRenewalPrice(CustomerService $customerService): ?float
    {
        $tld = $this->extractTld($customerService->domain);
        $domainTld = DomainTld::where('extension', $tld)->first();

        return $domainTld ? (float) $domainTld->renewal_price : null;
    }

    private function buildRegistrantContact($user): WhoisContact
    {
        $nameParts = $this->splitName($user->name);
        $companyType = $user->company ? 'ANDERS' : '';

        return (new WhoisContact())
            ->setType(WhoisContact::CONTACT_TYPE_REGISTRANT)
            ->setFirstName($nameParts['first'])
            ->setLastName($nameParts['last'])
            ->setCompanyName((string) $user->company)
            ->setCompanyKvk((string) $user->kvk_number)
            ->setCompanyType($companyType)
            ->setStreet((string) $user->street)
            ->setNumber((string) $user->house_number)
            ->setPostalCode((string) $user->postal_code)
            ->setCity((string) $user->city)
            ->setPhoneNumber((string) $user->phone)
            ->setEmail((string) $user->email)
            ->setCountry(strtoupper((string) $user->country));
    }

    private function splitName(string $name): array
    {
        $parts = explode(' ', trim($name), 2);

        return [
            'first' => $parts[0] ?? '',
            'last' => $parts[1] ?? '',
        ];
    }

    /**
     * @return Nameserver[]
     */
    private function buildNameservers(): array
    {
        $raw = BillingSetting::valueFor('domain_default_nameservers', BillingSetting::valueFor('transip_default_nameservers', ''));
        $hosts = array_filter(array_map('trim', explode(',', $raw)));

        if (empty($hosts)) {
            return [];
        }

        return array_map(function (string $host) {
            return (new Nameserver())->setHostname($host);
        }, $hosts);
    }
}
