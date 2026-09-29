<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\DomainAuditLog;
use App\Models\DomainRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class DomainHostingService
{
    public function details(DomainRegistration $domain): array
    {
        $hosting = $domain->hostedCustomerService;

        if (! $hosting) {
            return ['linked' => false];
        }

        return [
            'linked' => true,
            'package' => $hosting->service->title ?? '-',
            'status' => $hosting->isActive() ? 'actief' : $hosting->status,
            'server' => $hosting->external_username ?? '-',
            'main_domain' => $hosting->domain ?? $domain->domain_name,
            'customer_service_id' => $hosting->id,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function availableServicesFor(DomainRegistration $domain): array
    {
        return CustomerService::where('user_id', $domain->user_id)
            ->whereNull('cancelled_at')
            ->where('status', '!=', 'cancelled')
            ->whereHas('service', fn ($q) => $q->where('fulfillment_type', 'hosting'))
            ->whereDoesntHave('hostedDomainRegistrations')
            ->with('service')
            ->get()
            ->map(fn (CustomerService $service) => [
                'id' => $service->id,
                'label' => ($service->service->title ?? 'Hosting').' — '.$service->domain,
            ])
            ->all();
    }

    public function link(DomainRegistration $domain, int $customerServiceId, ?User $user = null): array
    {
        $service = CustomerService::where('user_id', $domain->user_id)
            ->where('id', $customerServiceId)
            ->whereHas('service', fn ($q) => $q->where('fulfillment_type', 'hosting'))
            ->first();

        if (! $service) {
            return ['success' => false, 'message' => 'Hostingpakket niet gevonden of niet beschikbaar.'];
        }

        try {
            $domain->update([
                'hosted_customer_service_id' => $service->id,
            ]);

            if (! $service->domain) {
                $service->update(['domain' => $domain->domain_name]);
            }

            DomainAuditLog::create([
                'domain_registration_id' => $domain->id,
                'user_id' => $user?->id,
                'action' => 'hosting_linked',
                'status' => 'completed',
                'after' => ['customer_service_id' => $service->id],
                'note' => 'Hosting gekoppeld aan domein.',
                'ip_address' => request()?->ip(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Domain hosting link failed', [
                'domain_registration_id' => $domain->id,
                'customer_service_id' => $customerServiceId,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Koppelen mislukt: '.$e->getMessage()];
        }

        return ['success' => true, 'message' => 'Hosting gekoppeld.'];
    }

    public function unlink(DomainRegistration $domain, ?User $user = null): array
    {
        try {
            $before = $domain->hosted_customer_service_id;

            $domain->update([
                'hosted_customer_service_id' => null,
            ]);

            DomainAuditLog::create([
                'domain_registration_id' => $domain->id,
                'user_id' => $user?->id,
                'action' => 'hosting_unlinked',
                'status' => 'completed',
                'before' => ['customer_service_id' => $before],
                'note' => 'Hosting ontkoppeld van domein. Website- en maildata blijven bestaan.',
                'ip_address' => request()?->ip(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Domain hosting unlink failed', [
                'domain_registration_id' => $domain->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Ontkoppelen mislukt: '.$e->getMessage()];
        }

        return ['success' => true, 'message' => 'Hosting ontkoppeld.'];
    }
}
