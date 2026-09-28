<?php

namespace App\Console\Commands;

use App\Models\BillingSetting;
use App\Services\Domains\TransIpService;
use Illuminate\Console\Command;
use Throwable;
use Transip\Api\Library\Exception\ApiException;
use Transip\Api\Library\Exception\HttpRequestException;

class TransIpTestConnection extends Command
{
    protected $signature = 'transip:test-connection';

    protected $description = 'Test de verbinding met de TransIP API en toon gedetailleerde foutmeldingen';

    public function handle(TransIpService $service): int
    {
        if (! $service->isConfigured()) {
            $this->error('TransIP is nog niet geconfigureerd in BillingSetting.');
            $this->line('Vul in het adminpaneel in: Instellingen → Integraties → TransIP');

            return self::FAILURE;
        }

        $this->info('Gebruikersnaam: '.BillingSetting::valueFor('transip_username'));
        $this->info('Whitelist-only: '.(BillingSetting::boolean('transip_whitelist_only') ? 'ja' : 'nee'));
        $this->info('Private key aanwezig: '.(BillingSetting::encryptedValueFor('transip_private_key') !== '' ? 'ja' : 'nee'));
        $this->newLine();

        try {
            $api = $service->client();
            $success = $api->test()->test();

            if ($success === true) {
                $this->info('Verbinding met TransIP geslaagd.');

                return self::SUCCESS;
            }

            $this->error('TransIP verbinding mislukt (test() gaf geen true terug).');

            return self::FAILURE;
        } catch (ApiException|HttpRequestException $e) {
            $this->error('Fout van TransIP API:');
            $this->line($e->getMessage());

            if ($e instanceof ApiException) {
                $this->newLine();
                $this->warn('HTTP status: '.$e->response()->getStatusCode());
            }
        } catch (Throwable $e) {
            $this->error('Onverwachte fout:');
            $this->line($e->getMessage());
        }

        $this->newLine();
        $this->line('Controleer:');
        $this->bullet('Is het TransIP-account geactiveerd voor API-toegang?');
        $this->bullet('Is de private key correct gekopieerd (inclusief regelafbrekingen)?');
        $this->bullet('Staat het server-IP in de whitelist bij TransIP?');
        $this->bullet('Is "whitelist-only" correct gezet? (Ja = token alleen bruikbaar vanaf ge-whiteliste IP\'s)');

        return self::FAILURE;
    }

    private function bullet(string $text): void
    {
        $this->line('  - '.$text);
    }
}
