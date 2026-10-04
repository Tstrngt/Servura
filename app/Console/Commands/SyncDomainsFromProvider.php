<?php

namespace App\Console\Commands;

use App\Models\DomainRegistration;
use App\Services\DomainService;
use Illuminate\Console\Command;
use Throwable;

class SyncDomainsFromProvider extends Command
{
    protected $signature = 'domains:sync {--domain= : Synchroniseer alleen dit domein}';

    protected $description = 'Synchroniseert domeinstatus, verlengdatum en nameservers vanuit de domeinprovider';

    public function handle(DomainService $domainService): int
    {
        $query = DomainRegistration::query()
            ->whereIn('status', [
                DomainRegistration::STATUS_ACTIVE,
                DomainRegistration::STATUS_TRANSFER_PENDING,
                DomainRegistration::STATUS_TRANSFER_PROCESSING,
            ]);

        if ($domain = $this->option('domain')) {
            $query->where('domain_name', strtolower(trim($domain)));
        }

        $count = 0;
        $failed = 0;

        $query->chunkById(50, function ($domains) use ($domainService, &$count, &$failed) {
            foreach ($domains as $domain) {
                try {
                    $domainService->syncInfo($domain);
                    $count++;
                } catch (Throwable $e) {
                    $failed++;
                    $this->warn("{$domain->domain_name}: {$e->getMessage()}");
                }
            }
        });

        $this->info("Gesynchroniseerd: {$count} domeinen".($failed ? " ({$failed} mislukt)" : ''));

        return self::SUCCESS;
    }
}
