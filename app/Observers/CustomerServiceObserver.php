<?php

namespace App\Observers;

use App\Models\CustomerService;

class CustomerServiceObserver
{
    public function created(CustomerService $customerService): void
    {
        // Observer kept for future use. Invoice creation is now handled
        // through the Quote flow (quote → invoice → payment → activate services).
    }
}
