<?php

namespace App\Observers;

use App\Models\CustomerService;
use App\Services\InvoiceService;

class CustomerServiceObserver
{
    public function created(CustomerService $customerService): void
    {
        // Auto-generate invoice when a service is assigned to a customer
        if ($customerService->price && $customerService->price > 0) {
            $invoiceService = app(InvoiceService::class);
            $invoiceService->createFromCustomerService(
                $customerService,
                auth()->id()
            );
        }
    }
}
