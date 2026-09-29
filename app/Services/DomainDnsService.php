<?php

namespace App\Services;

use App\Models\DomainAuditLog;
use App\Models\DomainRegistration;
use App\Models\User;
use App\Services\Domains\DomainProviderFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DomainDnsService
{
    public function __construct(private DomainService $domainService)
    {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function records(DomainRegistration $domain): array
    {
        $provider = DomainProviderFactory::forDomain($domain->domain_name);

        if (! $provider || ! $provider->isConfigured()) {
            return [];
        }

        try {
            return $provider->getDnsEntries($domain->domain_name);
        } catch (Throwable $e) {
            Log::warning('Domain DNS fetch failed', [
                'domain_registration_id' => $domain->id,
                'domain' => $domain->domain_name,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function canManage(DomainRegistration $domain): bool
    {
        // If we already know Servura manages DNS from a previous sync, trust that.
        if ($domain->is_dns_managed_by_servura) {
            return true;
        }

        $provider = DomainProviderFactory::forDomain($domain->domain_name);

        if (! $provider || ! $provider->isConfigured()) {
            return false;
        }

        // For non-demo domains, fetch current provider info to see if DNS is editable.
        try {
            $info = $provider->getDomainInfo($domain->domain_name);

            return (bool) ($info['can_edit_dns'] ?? false);
        } catch (Throwable $e) {
            Log::warning('Domain DNS capability check failed', [
                'domain_registration_id' => $domain->id,
                'domain' => $domain->domain_name,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function updateRecords(DomainRegistration $domain, array $records, ?User $user = null): array
    {
        $provider = DomainProviderFactory::forDomain($domain->domain_name);

        if (! $provider || ! $provider->isConfigured()) {
            return ['success' => false, 'message' => 'Domeinprovider is niet geconfigureerd.'];
        }

        if (! $this->canManage($domain)) {
            return ['success' => false, 'message' => 'DNS kan alleen worden beheerd wanneer het domein Servura/TransIP nameservers gebruikt.'];
        }

        $before = ['dns_entries' => $this->records($domain)];

        try {
            DB::transaction(function () use ($domain, $records, $provider, $user, $before) {
                $provider->setDnsEntries($domain->domain_name, $records);

                DomainAuditLog::create([
                    'domain_registration_id' => $domain->id,
                    'user_id' => $user?->id,
                    'action' => 'dns_records_updated',
                    'status' => 'completed',
                    'before' => $before,
                    'after' => ['dns_entries' => $this->records($domain)],
                    'note' => 'DNS-records gewijzigd via provider.',
                    'ip_address' => request()?->ip(),
                ]);
            });
        } catch (Throwable $e) {
            Log::warning('Domain DNS records update failed', [
                'domain_registration_id' => $domain->id,
                'domain' => $domain->domain_name,
                'error' => $e->getMessage(),
            ]);
            DomainAuditLog::create([
                'domain_registration_id' => $domain->id,
                'user_id' => $user?->id,
                'action' => 'dns_records_updated',
                'status' => 'failed',
                'before' => $before,
                'after' => ['dns_entries' => $records],
                'note' => $e->getMessage(),
                'ip_address' => request()?->ip(),
            ]);

            return ['success' => false, 'message' => 'DNS-wijziging mislukt: '.$e->getMessage()];
        }

        return ['success' => true, 'message' => 'DNS-records bijgewerkt.'];
    }
}
