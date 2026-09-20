<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DirectAdminClient
{
    public function isConfigured(): bool
    {
        return filled(config('directadmin.url'))
            && filled(config('directadmin.username'))
            && filled(config('directadmin.password'))
            && filled(config('directadmin.shared_ip'));
    }

    public function createUser(array $data): array
    {
        return $this->request('CMD_API_ACCOUNT_USER', [
            'action' => 'create',
            'add' => 'Submit',
            'username' => $data['username'],
            'email' => $data['email'],
            'passwd' => $data['password'],
            'passwd2' => $data['password'],
            'domain' => $data['domain'],
            'package' => $data['package'],
            'ip' => config('directadmin.shared_ip'),
            'notify' => 'no',
        ]);
    }

    public function suspendUser(string $username): array
    {
        return $this->request('CMD_API_SELECT_USERS', [
            'location' => 'CMD_SELECT_USERS',
            'dosuspend' => 'yes',
            'select0' => $username,
        ]);
    }

    public function unsuspendUser(string $username): array
    {
        return $this->request('CMD_API_SELECT_USERS', [
            'location' => 'CMD_SELECT_USERS',
            'dounsuspend' => 'yes',
            'select0' => $username,
        ]);
    }

    private function request(string $endpoint, array $data): array
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('DirectAdmin is niet geconfigureerd.');
        }

        $response = $this->http()->asForm()->post(
            rtrim(config('directadmin.url'), '/') . '/' . $endpoint,
            $data + ['json' => 'yes']
        );
        $response->throw();
        $result = $response->json();
        if (!is_array($result)) {
            parse_str($response->body(), $result);
        }
        if ((int) ($result['error'] ?? 1) !== 0) {
            throw new \RuntimeException(trim(($result['text'] ?? 'DirectAdmin-fout') . ' ' . ($result['details'] ?? '')));
        }

        return $result;
    }

    private function http(): PendingRequest
    {
        $request = Http::withBasicAuth(config('directadmin.username'), config('directadmin.password'))
            ->acceptJson()
            ->timeout(config('directadmin.timeout', 20));

        return config('directadmin.verify_ssl', true) ? $request : $request->withoutVerifying();
    }
}
