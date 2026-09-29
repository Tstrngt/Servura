<?php

namespace App\Jobs;

use App\Models\DomainRegistration;
use App\Models\User;
use App\Services\DomainSelfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateDomainNameservers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $domainRegistrationId,
        public array $nameservers,
        public ?int $userId = null,
    ) {
    }

    public function handle(DomainSelfService $service): void
    {
        $domain = DomainRegistration::find($this->domainRegistrationId);
        if (! $domain) {
            return;
        }

        $user = $this->userId ? User::find($this->userId) : null;
        $result = $service->updateNameservers($domain, $this->nameservers, $user);

        if (! ($result['success'] ?? false)) {
            throw new \RuntimeException($result['message'] ?? 'Nameserver update failed.');
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('UpdateDomainNameservers job failed', [
            'domain_registration_id' => $this->domainRegistrationId,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);
    }
}
