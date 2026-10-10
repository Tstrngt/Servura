<?php

namespace App\Services\Domains;

use App\Models\BillingSetting;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenProviderClient
{
    public function configured(): bool
    {
        return BillingSetting::boolean('openprovider_enabled')
            && filled(BillingSetting::valueFor('openprovider_username', ''))
            && filled(BillingSetting::encryptedValueFor('openprovider_password', ''));
    }

    public function request(string $method, string $path, array $data = []): array
    {
        $response = $this->http()->send($method, ltrim($path, '/'), $method === 'GET' ? ['query' => $data] : ['json' => $data]);
        $response->throw();
        $payload = $response->json();

        if (($payload['code'] ?? 0) !== 0) {
            throw new RuntimeException($payload['desc'] ?? 'Openprovider heeft het verzoek geweigerd.');
        }

        return $payload['data'] ?? [];
    }

    public function clearToken(): void
    {
        Cache::forget($this->tokenKey());
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->withToken($this->token())
            ->timeout(30)
            ->retry(3, 1000);
    }

    private function token(): string
    {
        return Cache::remember($this->tokenKey(), now()->addMinutes(50), function () {
            $response = Http::baseUrl($this->baseUrl())
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post('auth/login', [
                    'username' => BillingSetting::valueFor('openprovider_username', ''),
                    'password' => BillingSetting::encryptedValueFor('openprovider_password', ''),
                    'ip' => '0.0.0.0',
                ]);
            $response->throw();
            $payload = $response->json();

            if (($payload['code'] ?? 0) !== 0 || blank(data_get($payload, 'data.token'))) {
                throw new RuntimeException($payload['desc'] ?? 'Openprovider-authenticatie mislukt.');
            }

            return $payload['data']['token'];
        });
    }

    private function baseUrl(): string
    {
        return BillingSetting::valueFor('openprovider_environment', 'sandbox') === 'production'
            ? 'https://api.openprovider.eu/v1/'
            : 'https://api.sandbox.openprovider.nl/v1beta/';
    }

    private function tokenKey(): string
    {
        return 'openprovider.token.'.BillingSetting::valueFor('openprovider_environment', 'sandbox').'.'.sha1(BillingSetting::valueFor('openprovider_username', ''));
    }
}
