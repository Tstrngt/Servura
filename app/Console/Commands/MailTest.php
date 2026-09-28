<?php

namespace App\Console\Commands;

use App\Models\BillingSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailTest extends Command
{
    protected $signature = 'mail:test {email?}';

    protected $description = 'Toon huidige mailconfiguratie en verstuur een testmail';

    public function handle(): int
    {
        $mailer = BillingSetting::valueFor('mail_mailer') ?: Config::get('mail.default', 'smtp');
        $this->info('Huidige mailer: '.$mailer);

        if ($mailer === 'log') {
            $this->warn('Mailer staat op "log". Mails worden alleen naar laravel.log geschreven, niet verstuurd.');
            $this->line('Wijzig in admin: Instellingen → E-mail → Mailer naar SMTP.');
        }

        $this->line('Host: '.(BillingSetting::valueFor('mail_host') ?: Config::get('mail.mailers.smtp.host', 'niet ingesteld')));
        $this->line('Poort: '.BillingSetting::integer('mail_port', Config::get('mail.mailers.smtp.port', 587)));
        $this->line('Encryptie: '.(BillingSetting::valueFor('mail_encryption') ?: 'tls'));
        $this->line('Gebruikersnaam: '.(BillingSetting::valueFor('mail_username') ?: 'niet ingesteld'));
        $this->line('Wachtwoord: '.(BillingSetting::encryptedValueFor('mail_password') ? 'ingesteld' : 'niet ingesteld'));
        $this->line('From adres: '.(BillingSetting::valueFor('mail_from_address') ?: Config::get('mail.from.address', 'niet ingesteld')));
        $this->line('From naam: '.(BillingSetting::valueFor('mail_from_name') ?: Config::get('mail.from.name', 'niet ingesteld')));
        $this->newLine();

        $to = $this->argument('email') ?? BillingSetting::valueFor('mail_from_address');

        if (! $to) {
            $this->error('Geen ontvanger opgegeven. Gebruik: php artisan mail:test jouw@adres.nl');

            return self::FAILURE;
        }

        $this->info('Testmail versturen naar: '.$to);

        try {
            Mail::raw('Dit is een testmail vanuit Servura. Als u dit ontvangt, werken de mailinstellingen.', function ($message) use ($to) {
                $message->to($to)->subject('Servura mailtest');
            });

            $this->info('Testmail succesvol geaccepteerd door de mailer.');

            if ($mailer === 'log') {
                $this->warn('Controleer storage/logs/laravel.log om de mailinhoud te zien.');
            }

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Testmail mislukt:');
            $this->line('Exception: '.get_class($e));
            $this->line('Bericht: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
