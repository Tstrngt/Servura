<?php

use App\Services\DunningService;
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
