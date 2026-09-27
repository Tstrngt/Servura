<?php

namespace App\Support;

use App\Models\Service;

/**
 * Default quote-builder values per website package.
 *
 * These defaults are matched against the labels from QuoteFormFields. When a
 * service slug has no mapping, the form starts blank for that service.
 */
class QuotePackageDefaults
{
    public static function for(Service $service): array
    {
        $config = config("pricing.packages.{$service->slug}", config("pricing.packages." . str_replace('-website', '', $service->slug), []));

        return match (str_replace('-website', '', $service->slug)) {
            'starter' => [
                'goal' => null,
                'pages' => $config['includedPagesLabel'] ?? "tot 4 pagina's",
                'visitors' => null,
                'design' => null,
                'features' => [
                    'CMS (zelf beheren)',
                    'Contact- / leadformulieren',
                    'SEO-basis',
                ],
                'content' => [],
            ],
            'business' => [
                'goal' => null,
                'pages' => $config['includedPagesLabel'] ?? "tot 7 pagina's",
                'visitors' => null,
                'design' => null,
                'features' => [
                    'CMS (zelf beheren)',
                    'Blog / nieuws',
                    'Contact- / leadformulieren',
                    'SEO-basis',
                ],
                'content' => [],
            ],
            'pro' => [
                'goal' => null,
                'pages' => $config['includedPagesLabel'] ?? "tot 10 pagina's",
                'visitors' => null,
                'design' => null,
                'features' => [
                    'CMS (zelf beheren)',
                    'Blog / nieuws',
                    'Contact- / leadformulieren',
                    'SEO-basis',
                    'Analytics',
                    'Portfolio / projectenmodule',
                ],
                'content' => [],
            ],
            default => [
                'goal' => null,
                'pages' => null,
                'visitors' => null,
                'design' => null,
                'features' => [],
                'content' => [],
            ],
        };
    }
}
