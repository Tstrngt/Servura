<?php

namespace App\Services\Domains;

/**
 * Central place to determine whether a domain should use demo/mock data.
 *
 * Only the exact domain below uses the demo provider; all other domains
 * talk to the real TransIP API.
 */
class DemoDomain
{
    public const DEMO_DOMAIN = 'servura-test.nl';

    public static function is(string $domain): bool
    {
        return mb_strtolower(self::normalize($domain)) === self::DEMO_DOMAIN;
    }

    private static function normalize(string $domain): string
    {
        $domain = mb_strtolower(trim($domain));
        $domain = preg_replace('#^(https?://|www\.)+#', '', $domain) ?? $domain;

        return $domain;
    }
}
