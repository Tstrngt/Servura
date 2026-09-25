<?php

namespace App\Services;

use App\Models\CustomerService;
use App\Models\Notification;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProvisioningService
{
    public function __construct(private DirectAdminClient $directAdmin)
    {
    }

    public function provision(CustomerService $customerService): void
    {
        $customerService->loadMissing(['user', 'service.serverConnection']);
        if ($customerService->service->fulfillment_type !== 'directadmin') {
            return;
        }
        if ($customerService->provisioning_status === 'active') {
            return;
        }
        if (!$customerService->domain || !$customerService->service->serverConnection || !$customerService->service->provider_package) {
            $this->fail($customerService, 'Domein, serverkoppeling of providerpakket ontbreekt.');
            return;
        }

        if ($customerService->service->serverConnection->provider !== 'directadmin') {
            $this->fail($customerService, 'De gekozen serverprovider wordt nog niet ondersteund.');
            return;
        }

        $username = $customerService->external_username ?: $this->generateUsername($customerService);
        $password = $customerService->external_password ?: Str::password(20, true, true, false);
        $customerService->update([
            'external_username' => $username,
            'external_password' => $password,
            'provisioning_status' => 'processing',
            'provisioning_error' => null,
        ]);

        try {
            $client = $this->directAdmin->using($customerService->service->serverConnection);
            try {
                $client->createUser([
                    'username' => $username,
                    'password' => $password,
                    'email' => $customerService->user->email,
                    'domain' => $customerService->domain,
                    'package' => $customerService->service->provider_package,
                ]);
            } catch (\Throwable $createException) {
                // DirectAdmin kan een foutmelding teruggeven terwijl het account
                // al (deels) is aangemaakt. Controleer daarom altijd of de user
                // daadwerkelijk bestaat voordat we de provisioning als mislukt
                // markeren. "username already exists" betekent ook dat het
                // account er is (bijv. restant van een eerdere poging).
                $responseMessage = $createException->getMessage();
                $alreadyExists = stripos($responseMessage, 'already exists') !== false
                    || stripos($responseMessage, 'bestaat al') !== false;
                $createdDespiteError = stripos($responseMessage, 'Unix User created successfully') !== false
                    && (stripos($responseMessage, 'Domain Created Successfully') !== false
                        || stripos($responseMessage, 'successful created') !== false);
                if (! $alreadyExists && ! $createdDespiteError && ! $client->userExists($username)) {
                    throw $createException;
                }
                $this->log($customerService, 'directadmin_waarschuwing', 'DirectAdmin meldde een fout, maar het account bestaat wel: ' . Str::limit($createException->getMessage(), 300));
            }
            $customerService->update([
                'provisioning_status' => 'active',
                'provisioned_at' => now(),
                'external_suspended_at' => null,
            ]);
            $this->log($customerService, 'directadmin_aangemaakt', 'DirectAdmin-account automatisch aangemaakt.');
            $this->sendCredentials($customerService->fresh(['user', 'service.serverConnection']));
        } catch (\Throwable $exception) {
            $message = $exception->getMessage();
            $isTimeout = stripos($message, 'timed out') !== false;
            $this->fail($customerService, $message, !$isTimeout, $isTimeout ? 'timeout' : 'error');
            throw $exception;
        }
    }

    public function suspend(CustomerService $customerService): void
    {
        $customerService->loadMissing('service.serverConnection');
        if ($customerService->service->fulfillment_type !== 'directadmin' || !$customerService->external_username || !$customerService->service->serverConnection) {
            return;
        }
        if ($customerService->provisioning_status === 'suspended') {
            if ($customerService->status === 'cancelled' && \App\Models\BillingSetting::integer('da_delete_after_days', 30) === 0) {
                $this->deleteAccount($customerService);
            }
            return;
        }

        try {
            $this->directAdmin->using($customerService->service->serverConnection)->suspendUser($customerService->external_username);
            $customerService->update([
                'provisioning_status' => 'suspended',
                'external_suspended_at' => now(),
                'provisioning_error' => null,
            ]);
            $this->log($customerService, 'directadmin_geschorst', 'DirectAdmin-account geschorst wegens wanbetaling.');
            app(\App\Services\CustomerNotificationService::class)->serviceSuspended(
                $customerService->user,
                $customerService->fresh(),
                $this->suspensionReason($customerService)
            );
            if ($customerService->status === 'cancelled' && \App\Models\BillingSetting::integer('da_delete_after_days', 30) === 0) {
                $this->deleteAccount($customerService->fresh());
            }
        } catch (\Throwable $exception) {
            $this->fail($customerService, $exception->getMessage(), false);
            throw $exception;
        }
    }

    public function unsuspend(CustomerService $customerService): void
    {
        $customerService->loadMissing('service.serverConnection');
        if ($customerService->service->fulfillment_type !== 'directadmin' || !$customerService->external_username || !$customerService->service->serverConnection) {
            return;
        }
        if (!in_array($customerService->provisioning_status, ['suspended', 'failed'], true)) {
            return;
        }

        try {
            $this->directAdmin->using($customerService->service->serverConnection)->unsuspendUser($customerService->external_username);
            $customerService->update([
                'provisioning_status' => 'active',
                'external_suspended_at' => null,
                'provisioning_error' => null,
            ]);
            $this->log($customerService, 'directadmin_hersteld', 'DirectAdmin-account hersteld na betaling.');
        } catch (\Throwable $exception) {
            $this->fail($customerService, $exception->getMessage());
            throw $exception;
        }
    }

    /**
     * Permanently delete DirectAdmin accounts for cancelled services whose
     * retention period has passed. Runs daily via the scheduler.
     */
    public function deleteDueCancelled(): int
    {
        $days = \App\Models\BillingSetting::integer('da_delete_after_days', 30);
        $deleted = 0;

        CustomerService::query()
            ->where('status', 'cancelled')
            ->where('provisioning_status', 'suspended')
            ->whereNotNull('external_username')
            ->whereDate('external_suspended_at', '<=', now()->subDays($days))
            ->with(['service.serverConnection', 'user'])
            ->chunkById(50, function ($services) use (&$deleted) {
                foreach ($services as $customerService) {
                    if ($customerService->service->fulfillment_type !== 'directadmin' || ! $customerService->service->serverConnection) {
                        continue;
                    }

                    if ($this->deleteAccount($customerService)) {
                        $deleted++;
                    }
                }
            });

        return $deleted;
    }

    private function deleteAccount(CustomerService $customerService): bool
    {
        $customerService->loadMissing('service.serverConnection');
        if ($customerService->service->fulfillment_type !== 'directadmin' || ! $customerService->external_username || ! $customerService->service->serverConnection) {
            return false;
        }

        try {
            $this->directAdmin->using($customerService->service->serverConnection)
                ->deleteUser($customerService->external_username);

            $customerService->update([
                'provisioning_status' => 'deleted',
                'provisioning_error' => null,
            ]);
            $this->log($customerService, 'directadmin_verwijderd', 'DirectAdmin-account definitief verwijderd na opzegging.');

            return true;
        } catch (\Throwable $exception) {
            report($exception);
            $this->log($customerService, 'directadmin_verwijderen_mislukt', 'Verwijderen DirectAdmin-account mislukt: ' . Str::limit($exception->getMessage(), 500));

            return false;
        }
    }

    private function generateUsername(CustomerService $customerService): string
    {
        $prefix = strtolower(preg_replace('/[^a-z0-9]/i', '', explode('.', $customerService->domain)[0]));
        $prefix = preg_match('/^[a-z]/', $prefix) ? $prefix : 'usr' . $prefix;

        return substr($prefix, 0, 4) . str_pad(base_convert((string) $customerService->id, 10, 36), 4, '0', STR_PAD_LEFT);
    }

    private function suspensionReason(CustomerService $customerService): string
    {
        return match ($customerService->suspension_reason) {
            'payment_overdue' => 'openstaande betaling',
            'provisioning_failed' => 'probleem bij het activeren van de dienst',
            'domain_required' => 'domein ontbreekt',
            'manual' => 'handmatige actie door ons team',
            default => 'openstaande betaling of technische reden',
        };
    }

    private function fail(CustomerService $customerService, string $message, bool $suspendService = true, string $errorType = 'error'): void
    {
        $data = [
            'provisioning_status' => $errorType === 'timeout' ? 'processing' : 'failed',
            'provisioning_error' => Str::limit($message, 1000),
        ];
        if ($suspendService) {
            $data['status'] = 'suspended';
            $data['suspension_reason'] = 'provisioning_failed';
        }
        $customerService->update($data);
        $this->log($customerService, 'directadmin_mislukt', 'DirectAdmin-actie mislukt: ' . Str::limit($message, 500));
    }

    private function log(CustomerService $customerService, string $action, string $description): void
    {
        TransactionLog::create([
            'user_id' => $customerService->user_id,
            'loggable_type' => CustomerService::class,
            'loggable_id' => $customerService->id,
            'action' => $action,
            'description' => $description,
        ]);
    }

    private function sendCredentials(CustomerService $customerService): void
    {
        Notification::notify(
            $customerService->user,
            'service',
            'Uw hostingaccount is gereed',
            "Het hostingaccount voor {$customerService->domain} is aangemaakt.",
            route('customer.dashboard')
        );

        app(\App\Services\CustomerNotificationService::class)->serviceActivated($customerService->user, $customerService->fresh());
    }
}
