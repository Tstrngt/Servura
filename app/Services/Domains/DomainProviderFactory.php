<?php

namespace App\Services\Domains;

use Illuminate\Contracts\Container\BindingResolutionException;

class DomainProviderFactory
{
    public static function make(string $providerName): ?DomainProvider
    {
        return match ($providerName) {
            'transip' => app(TransIpProvider::class),
            default => null,
        };
    }

    public static function default(): ?DomainProvider
    {
        return static::make('transip');
    }
}
