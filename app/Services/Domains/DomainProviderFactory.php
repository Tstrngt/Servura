<?php

namespace App\Services\Domains;

use Illuminate\Contracts\Container\BindingResolutionException;

class DomainProviderFactory
{
    public static function make(string $providerName): ?DomainProvider
    {
        return match ($providerName) {
            'transip' => app(TransIpProvider::class),
            'demo' => app(DemoDomainProvider::class),
            default => null,
        };
    }

    public static function default(): ?DomainProvider
    {
        return static::make('transip');
    }

    /**
     * Resolve the correct provider for a given domain name.
     * The demo domain uses the mock provider; everything else uses TransIP.
     */
    public static function forDomain(string $domain): ?DomainProvider
    {
        if (DemoDomain::is($domain)) {
            return static::make('demo');
        }

        return static::default();
    }
}
