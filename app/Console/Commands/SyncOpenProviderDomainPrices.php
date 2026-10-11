<?php

namespace App\Console\Commands;

use App\Jobs\SyncOpenProviderDomainPrices as SyncPricesJob;
use Illuminate\Console\Command;

class SyncOpenProviderDomainPrices extends Command
{
    protected $signature = 'domains:sync-prices {--now : Voer synchronisatie direct uit}';
    protected $description = 'Synchroniseer domeininkoop- en verkoopprijzen met Openprovider';

    public function handle(): int
    {
        if ($this->option('now')) {
            app(\App\Services\OpenProviderPriceSyncService::class)->run();
        } else {
            SyncPricesJob::dispatch();
        }

        $this->info('Openprovider-prijssynchronisatie gestart.');

        return self::SUCCESS;
    }
}
