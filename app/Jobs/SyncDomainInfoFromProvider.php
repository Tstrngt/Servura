<?php

namespace App\Jobs;

use App\Models\DomainRegistration;
use App\Services\DomainSelfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncDomainInfoFromProvider implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $domainRegistrationId)
    {
    }

    public function handle(DomainSelfService $service): void
    {
        $domain = DomainRegistration::find($this->domainRegistrationId);
        if (! $domain) {
            return;
        }

        $service->syncInfo($domain);
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('SyncDomainInfoFromProvider job failed', [
            'domain_registration_id' => $this->domainRegistrationId,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);
    }
}
