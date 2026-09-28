<?php

namespace App\Services\Domains;

use App\Models\BillingSetting;
use Transip\Api\Library\TransipAPI;

class TransIpService
{
    public function isConfigured(): bool
    {
        return $this->username() !== '' && $this->privateKey() !== '';
    }

    public function username(): string
    {
        return (string) BillingSetting::valueFor('transip_username', '');
    }

    public function privateKey(): string
    {
        return (string) BillingSetting::encryptedValueFor('transip_private_key', '');
    }

    public function whitelistOnly(): bool
    {
        return BillingSetting::boolean('transip_whitelist_only');
    }

    public function client(): TransipAPI
    {
        $privateKey = $this->privateKey();

        if ($privateKey === '') {
            throw new \RuntimeException('TransIP private key is not configured.');
        }

        $api = new TransipAPI(
            $this->username(),
            $privateKey,
            $this->whitelistOnly(),
        );

        // Force read-only mode for this integration phase.
        $api->setReadOnlyMode(true);

        return $api;
    }
}
