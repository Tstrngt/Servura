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
        $customerService->loadMissing(['user', 'service']);
        if ($customerService->service->fulfillment_type !== 'directadmin') {
            return;
        }
        if ($customerService->provisioning_status === 'active') {
            return;
        }
        if (!$customerService->domain || !$customerService->service->directadmin_package) {
            $this->fail($customerService, 'Domein of DirectAdmin-pakket ontbreekt.');
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
            $this->directAdmin->createUser([
                'username' => $username,
                'password' => $password,
                'email' => $customerService->user->email,
                'domain' => $customerService->domain,
                'package' => $customerService->service->directadmin_package,
            ]);
            $customerService->update([
                'provisioning_status' => 'active',
                'provisioned_at' => now(),
                'external_suspended_at' => null,
            ]);
            $this->log($customerService, 'directadmin_aangemaakt', 'DirectAdmin-account automatisch aangemaakt.');
            $this->sendCredentials($customerService->fresh(['user', 'service']));
        } catch (\Throwable $exception) {
            $this->fail($customerService, $exception->getMessage());
            throw $exception;
        }
    }

    public function suspend(CustomerService $customerService): void
    {
        if ($customerService->service->fulfillment_type !== 'directadmin' || !$customerService->external_username) {
            return;
        }
        if ($customerService->provisioning_status === 'suspended') {
            return;
        }

        try {
            $this->directAdmin->suspendUser($customerService->external_username);
            $customerService->update([
                'provisioning_status' => 'suspended',
                'external_suspended_at' => now(),
                'provisioning_error' => null,
            ]);
            $this->log($customerService, 'directadmin_geschorst', 'DirectAdmin-account geschorst wegens wanbetaling.');
        } catch (\Throwable $exception) {
            $this->fail($customerService, $exception->getMessage(), false);
            throw $exception;
        }
    }

    public function unsuspend(CustomerService $customerService): void
    {
        if ($customerService->service->fulfillment_type !== 'directadmin' || !$customerService->external_username) {
            return;
        }
        if ($customerService->provisioning_status !== 'suspended') {
            return;
        }

        try {
            $this->directAdmin->unsuspendUser($customerService->external_username);
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

    private function generateUsername(CustomerService $customerService): string
    {
        $prefix = strtolower(preg_replace('/[^a-z0-9]/i', '', explode('.', $customerService->domain)[0]));
        $prefix = preg_match('/^[a-z]/', $prefix) ? $prefix : 'usr' . $prefix;

        return substr($prefix, 0, 4) . str_pad(base_convert((string) $customerService->id, 10, 36), 4, '0', STR_PAD_LEFT);
    }

    private function fail(CustomerService $customerService, string $message, bool $suspendService = true): void
    {
        $data = [
            'provisioning_status' => 'failed',
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

        try {
            Mail::send('hosting-credentials-email', ['customerService' => $customerService], function ($message) use ($customerService) {
                $message->to($customerService->user->email, $customerService->user->name)
                    ->subject("Hostingaccount voor {$customerService->domain}");
            });
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
