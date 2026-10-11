<?php

namespace App\Jobs;

use App\Models\DomainRegistration;
use App\Services\DomainDnsService;
use App\Services\DomainService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SyncDomainManagementData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public int $domainRegistrationId)
    {
    }

    public function handle(DomainService $domains, DomainDnsService $dns): void
    {
        $domain = DomainRegistration::find($this->domainRegistrationId);
        if (! $domain) {
            return;
        }

        Cache::put("domain.management.contacts.{$domain->id}", $domains->contacts($domain), now()->addMinutes(15));
        Cache::put("domain.management.capabilities.{$domain->id}", $domains->tldCapabilities($domain), now()->addMinutes(15));
        Cache::put("domain.management.dns_managed.{$domain->id}", $dns->canManage($domain), now()->addMinutes(15));

        $dnsRecords = $dns->fetchRecords($domain);
        if ($dnsRecords !== null) {
            Cache::put("domain.management.dns.{$domain->id}", $dnsRecords, now()->addMinutes(5));
            Cache::put("domain.management.dns_loaded.{$domain->id}", true, now()->addMinutes(5));
        }
    }
}
