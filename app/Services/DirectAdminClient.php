<?php

namespace App\Services;

use App\Models\ServerConnection;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DirectAdminClient
{
    private ?ServerConnection $connection = null;

    public function using(?ServerConnection $connection): self
    {
        $client = clone $this;
        $client->connection = $connection;

        return $client;
    }

    public function isConfigured(): bool
    {
        return filled($this->value('url'))
            && filled($this->value('username'))
            && filled($this->value('password'))
            && filled($this->value('shared_ip'));
    }

    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('De serverkoppeling is niet volledig ingevuld.');
        }

        $url = rtrim($this->value('url'), '/') . '/CMD_API_LOGIN_TEST';

        try {
            $response = $this->http()->get($url, ['json' => 'yes']);
            $response->throw();
        } catch (\Throwable $e) {
            Log::error('DirectAdmin test connection failed', [
                'url' => $url,
                'username' => $this->value('username'),
                'verify_ssl' => $this->value('verify_ssl'),
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Kan geen verbinding maken met DirectAdmin: ' . $e->getMessage(), 0, $e);
        }

        $body = $response->body();
        Log::info('DirectAdmin test connection raw response', ['url' => $url, 'body' => $body]);

        return $this->parseResponse($body);
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
            'ip' => $this->value('shared_ip'),
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

        try {
            $response = $this->http()->asForm()->post(
                rtrim($this->value('url'), '/') . '/' . $endpoint,
                $data + ['json' => 'yes']
            );
            $response->throw();
        } catch (\Throwable $e) {
            throw new \RuntimeException('DirectAdmin-verzoek mislukt: ' . $e->getMessage(), 0, $e);
        }

        return $this->parseResponse($response->body());
    }

    private function parseResponse(string $body): array
    {
        $body = trim($body);
        $result = json_decode($body, true);
        if (!is_array($result)) {
            parse_str($body, $result);
        }

        $hasError = array_key_exists('error', $result);
        $error = (int) ($result['error'] ?? 1);
        $success = trim((string) ($result['success'] ?? ''));

        if ($hasError && $error !== 0) {
            $message = trim(($result['text'] ?? '') . ' ' . ($result['details'] ?? ''));
            if ($message === '') {
                $message = 'DirectAdmin-fout (ruwe response: ' . mb_strimwidth($body, 0, 500) . ')';
            }
            throw new \RuntimeException($message);
        }

        if (!$hasError && $success !== '' && stripos($success, 'Login OK') !== false) {
            return $result;
        }

        if (!$hasError && $success === '') {
            return $result;
        }

        if ($hasError && $error === 0) {
            return $result;
        }

        throw new \RuntimeException('DirectAdmin-fout (ruwe response: ' . mb_strimwidth($body, 0, 500) . ')');
    }

    private function value(string $key): mixed
    {
        return $this->connection?->{$key} ?? config("directadmin.{$key}");
    }

    private function http(): PendingRequest
    {
        $request = Http::withBasicAuth($this->value('username'), $this->value('password'))
            ->timeout((int) ($this->value('timeout') ?: 20));

        return $this->value('verify_ssl') ? $request : $request->withoutVerifying();
    }
}
