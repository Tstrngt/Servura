<?php

use App\Models\CustomerService;
use App\Services\DunningService;
use App\Services\ProvisioningService;
use App\Services\RenewalService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('billing:renewals', function (RenewalService $renewals) {
    $result = $renewals->run();
    $this->table(['Aangemaakt', 'Incasso', 'Betaallink', 'Mislukt'], [[
        $result['created'], $result['automatic'], $result['payment_links'], $result['failed'],
    ]]);
})->purpose('Maak verschuldigde verlengingsfacturen aan en start de betaling');

Artisan::command('billing:dunning', function (DunningService $dunning) {
    $result = $dunning->run();
    $this->table(['Vervallen', 'Herinneringen', 'Geschorst', 'Mislukt'], [[
        $result['overdue'], $result['reminders'], $result['suspended'], $result['failed'],
    ]]);
})->purpose('Verwerk vervallen facturen, herinneringen en suspension');

Artisan::command('provisioning:retry {service?}', function (ProvisioningService $provisioning, ?int $service = null) {
    $query = CustomerService::with(['user', 'service'])
        ->where('provisioning_status', 'failed')
        ->whereHas('service', fn ($query) => $query->where('fulfillment_type', 'directadmin'));
    if ($service) {
        $query->whereKey($service);
    }

    $succeeded = 0;
    $failed = 0;
    foreach ($query->get() as $customerService) {
        try {
            if (!$customerService->provisioned_at) {
                $provisioning->provision($customerService);
            } elseif ($customerService->status === 'suspended' && $customerService->suspension_reason === 'non_payment') {
                $provisioning->suspend($customerService);
            } else {
                $provisioning->unsuspend($customerService);
            }
            $succeeded++;
        } catch (Throwable $exception) {
            $failed++;
            $this->error("Dienst {$customerService->id}: {$exception->getMessage()}");
        }
    }
    $this->info("Provisioning voltooid: {$succeeded} geslaagd, {$failed} mislukt.");
})->purpose('Probeer mislukte DirectAdmin-provisioning opnieuw');
