<?php

namespace App\Services;

use App\Models\DomainAuditLog;
use App\Models\DomainRegistration;
use App\Models\User;
use App\Services\Domains\DomainProviderFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DomainSelfService
{
    public function syncInfo(DomainRegistration $domain): DomainRegistration
    {
        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            $this->logAudit($domain, action: 'sync_info', status: 'failed', note: 'Provider not configured.');

            return $domain;
        }

        try {
            $info = $provider->getDomainInfo($domain->domain_name);
        } catch (Throwable $e) {
            Log::warning('Domain info sync failed', [
                'domain_registration_id' => $domain->id,
                'domain' => $domain->domain_name,
                'error' => $e->getMessage(),
            ]);
            $this->logAudit($domain, action: 'sync_info', status: 'failed', note: $e->getMessage());

            return $domain;
        }

        $before = [
            'nameservers' => $domain->current_nameservers,
            'status' => $domain->status,
            'expires_at' => $domain->expires_at?->toDateString(),
            'registered_at' => $domain->registered_at?->toDateString(),
            'registrar_lock' => $domain->registrar_lock,
            'is_dns_managed_by_servura' => $domain->is_dns_managed_by_servura,
        ];

        $nameservers = $info['nameservers'] ?? [];
        $isManaged = $this->isDnsManagedByServura($nameservers);

        $domain->update([
            'status' => $this->mapProviderStatus($info['status'] ?? 'active'),
            'registered_at' => $info['registration_date'] ?? $domain->registered_at,
            'expires_at' => $info['renewal_date'] ?? $domain->expires_at,
            'current_nameservers' => $nameservers,
            'is_dns_managed_by_servura' => $isManaged,
            'registrar_lock' => $info['is_transfer_locked'] ?? false,
            'provider_info_synced_at' => now(),
        ]);

        $this->logAudit($domain, action: 'sync_info', status: 'completed', before: $before, after: [
            'nameservers' => $nameservers,
            'status' => $domain->status,
            'expires_at' => $domain->expires_at?->toDateString(),
            'registered_at' => $domain->registered_at?->toDateString(),
            'registrar_lock' => $domain->registrar_lock,
            'is_dns_managed_by_servura' => $domain->is_dns_managed_by_servura,
        ]);

        return $domain;
    }

    public function updateNameservers(DomainRegistration $domain, array $nameservers, ?User $user = null): array
    {
        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return ['success' => false, 'message' => 'Domeinprovider is niet geconfigureerd.'];
        }

        $capabilities = $provider->getTldCapabilities($domain->tld);
        if (! in_array(\Transip\Api\Library\Entity\Tld::CAPABILITY_CANSETNAMESERVERS, $capabilities['capabilities'] ?? [], true)) {
            return ['success' => false, 'message' => 'Deze extensie ondersteunt geen nameserverwijzigingen via de API.'];
        }

        $before = [
            'nameservers' => $domain->current_nameservers,
        ];

        try {
            DB::transaction(function () use ($domain, $nameservers, $provider, $user) {
                $provider->setNameservers($domain->domain_name, $nameservers);

                $domain->update([
                    'current_nameservers' => $nameservers,
                    'is_dns_managed_by_servura' => $this->isDnsManagedByServura($nameservers),
                    'provider_info_synced_at' => now(),
                ]);

                $this->logAudit(
                    $domain,
                    action: 'nameservers_updated',
                    status: 'completed',
                    before: ['nameservers' => $before['nameservers']],
                    after: ['nameservers' => $nameservers],
                    note: 'Nameservers gewijzigd via TransIP.',
                    user: $user
                );
            });
        } catch (Throwable $e) {
            Log::warning('Domain nameserver update failed', [
                'domain_registration_id' => $domain->id,
                'domain' => $domain->domain_name,
                'error' => $e->getMessage(),
            ]);
            $this->logAudit($domain, action: 'nameservers_updated', status: 'failed', before: $before, after: ['nameservers' => $nameservers], note: $e->getMessage(), user: $user);

            return ['success' => false, 'message' => 'Wijziging mislukt: '.$e->getMessage()];
        }

        return ['success' => true, 'message' => 'Nameservers bijgewerkt.'];
    }

    public function updateHolderContacts(DomainRegistration $domain, array $contacts, ?User $user = null): array
    {
        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return ['success' => false, 'message' => 'Domeinprovider is niet geconfigureerd.'];
        }

        $capabilities = $provider->getTldCapabilities($domain->tld);
        if (! in_array(\Transip\Api\Library\Entity\Tld::CAPABILITY_CANSETCONTACTS, $capabilities['capabilities'] ?? [], true)) {
            return ['success' => false, 'message' => 'Deze extensie ondersteunt geen contactwijzigingen via de API.'];
        }

        $before = ['contacts' => $provider->getContacts($domain->domain_name)];

        try {
            DB::transaction(function () use ($domain, $contacts, $provider, $user, $before) {
                $provider->setContacts($domain->domain_name, $contacts);

                $this->logAudit(
                    $domain,
                    action: 'holder_contacts_updated',
                    status: 'completed',
                    before: $before,
                    after: ['contacts' => $contacts],
                    note: 'Houdergegevens gewijzigd via TransIP.',
                    user: $user
                );
            });
        } catch (Throwable $e) {
            Log::warning('Domain holder contacts update failed', [
                'domain_registration_id' => $domain->id,
                'domain' => $domain->domain_name,
                'error' => $e->getMessage(),
            ]);
            $this->logAudit($domain, action: 'holder_contacts_updated', status: 'failed', before: $before, after: ['contacts' => $contacts], note: $e->getMessage(), user: $user);

            return ['success' => false, 'message' => 'Wijziging mislukt: '.$e->getMessage()];
        }

        return ['success' => true, 'message' => 'Houdergegevens bijgewerkt.'];
    }

    public function getAuthCode(DomainRegistration $domain, ?User $user = null): ?string
    {
        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return null;
        }

        $code = $provider->getAuthCode($domain->domain_name);

        if ($code !== null) {
            $this->logAudit($domain, action: 'auth_code_viewed', status: 'completed', note: 'Verhuiscode opgevraagd.', user: $user);
        }

        return $code;
    }

    public function tldCapabilities(DomainRegistration $domain): array
    {
        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            return [];
        }

        return $provider->getTldCapabilities($domain->tld);
    }

    public function logAudit(
        DomainRegistration $domain,
        string $action,
        string $status = 'completed',
        ?array $before = null,
        ?array $after = null,
        ?string $note = null,
        ?User $user = null,
    ): void {
        DomainAuditLog::create([
            'domain_registration_id' => $domain->id,
            'user_id' => $user?->id,
            'action' => $action,
            'before' => $before,
            'after' => $after,
            'note' => $note,
            'ip_address' => request()?->ip(),
            'status' => $status,
        ]);
    }

    private function isDnsManagedByServura(array $nameservers): bool
    {
        // TODO: compare against the configured Servura/TransIP nameserver hostnames.
        return false;
    }

    private function mapProviderStatus(string $status): string
    {
        return match (strtolower($status)) {
            'active' => DomainRegistration::STATUS_ACTIVE,
            'pending' => DomainRegistration::STATUS_PENDING,
            'transfer_active' => DomainRegistration::STATUS_TRANSFER_ACTIVE,
            'transfer_pending' => DomainRegistration::STATUS_TRANSFER_PENDING,
            default => $status,
        };
    }
}
