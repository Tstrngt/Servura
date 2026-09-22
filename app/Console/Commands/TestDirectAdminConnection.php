<?php

namespace App\Console\Commands;

use App\Models\ServerConnection;
use App\Services\DirectAdminClient;
use Illuminate\Console\Command;

class TestDirectAdminConnection extends Command
{
    protected $signature = 'directadmin:test {connection : ID of the server connection}';

    protected $description = 'Test a DirectAdmin server connection and show the raw response';

    public function handle(DirectAdminClient $client): int
    {
        $connection = ServerConnection::find($this->argument('connection'));

        if (!$connection) {
            $this->error('Serverkoppeling niet gevonden.');
            return self::FAILURE;
        }

        if ($connection->provider !== 'directadmin') {
            $this->error('Deze koppeling is geen DirectAdmin-provider.');
            return self::FAILURE;
        }

        $this->info("Test verbinding: {$connection->name}");
        $this->info("URL: {$connection->url}");
        $this->info("Gebruiker: {$connection->username}");
        $this->info("SSL verifiëren: " . ($connection->verify_ssl ? 'ja' : 'nee'));

        try {
            $result = $client->using($connection)->testConnection();
            $this->info('Verbinding geslaagd.');
            $this->newLine();
            $this->line('Response:');
            print_r($result);
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Verbinding mislukt: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Geprobeerd met SSL-verificatie: ' . ($connection->verify_ssl ? 'aan' : 'uit'));
            $this->warn('Controleer:');
            $this->line('- SSL-verificatie: probeer eerst met "SSL verifiëren" uit in het adminpanel.');
            $this->line('- Gebruikersnaam en wachtwoord.');
            $this->line('- Of de URL bereikbaar is vanaf deze server.');

            if (!$connection->verify_ssl) {
                $this->newLine();
                $this->warn('SSL-verificatie staat al uit; controleer de gebruikersnaam en het wachtwoord.');
            }

            return self::FAILURE;
        }
    }
}
