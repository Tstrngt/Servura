<?php

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
