<?php

namespace App\Console\Commands;

use App\Services\Domains\DomainProviderFactory;
use Illuminate\Console\Command;
use Throwable;

class TransIpCheckDomain extends Command
{
    protected $signature = 'transip:check-domain {domain}';

    protected $description = 'Controleer beschikbaarheid van een domein via TransIP (read-only)';

    public function handle(): int
    {
        $domain = $this->argument('domain');

        $provider = DomainProviderFactory::default();

        if (! $provider || ! $provider->isConfigured()) {
            $this->error('TransIP is niet geconfigureerd.');

            return self::FAILURE;
        }

        $this->info('Provider: '.$provider->name());
        $this->info('Domein: '.$domain);
        $this->newLine();

        try {
            $result = $provider->checkAvailability($domain);

            $this->info('Resultaat:');
            $this->line('  Domein: '.$result->domain);
            $this->line('  Status: '.$result->status);
            $this->line('  Beschikbaar: '.($result->available ? 'ja' : 'nee'));
            $this->line('  TLD: '.($result->tld ?? '-'));
            $this->line('  Acties: '.(empty($result->actions) ? '-' : implode(', ', $result->actions)));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Fout bij beschikbaarheidscheck:');
            $this->line('  Exception: '.get_class($e));
            $this->line('  Bericht: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
