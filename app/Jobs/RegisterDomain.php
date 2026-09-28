<?php

namespace App\Jobs;

use App\Models\CustomerService;
use App\Services\DomainRegistrationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisterDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $customerServiceId)
    {
    }

    public function handle(DomainRegistrationService $service): void
    {
        $customerService = CustomerService::find($this->customerServiceId);
        if (! $customerService) {
            return;
        }

        if ($customerService->service->fulfillment_type !== 'domain') {
            return;
        }

        $service->register($customerService);
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('RegisterDomain job failed', [
            'customer_service_id' => $this->customerServiceId,
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);
    }
}
