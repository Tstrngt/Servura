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
                'goal' => 'Visitekaartje / online brochure',
                'pages' => $config['includedPagesLabel'] ?? "tot 4 pagina's",
                'visitors' => 'Minder dan 1.000',
                'design' => $config['designDefault'] ?? 'Ik heb een logo en kleuren',
                'features' => [
                    'CMS (zelf beheren)',
                    'Contact- / leadformulieren',
                ],
                'content' => [
                    'Ik lever teksten en beelden zelf aan',
                ],
            ],
            'business' => [
                'goal' => 'Meer leads en aanvragen',
                'pages' => $config['includedPagesLabel'] ?? "tot 7 pagina's",
                'visitors' => '1.000 - 5.000',
                'design' => $config['designDefault'] ?? 'Ik heb een complete huisstijl',
                'features' => [
                    'CMS (zelf beheren)',
                    'Contact- / leadformulieren',
                    'SEO-basis',
                    'Blog / nieuws',
                ],
                'content' => [
                    'Ik lever teksten en beelden zelf aan',
                ],
            ],
            'pro' => [
                'goal' => 'Producten of diensten verkopen',
                'pages' => $config['includedPagesLabel'] ?? "tot 10 pagina's",
                'visitors' => '5.000 - 25.000',
                'design' => $config['designDefault'] ?? 'Ik wil hulp bij de vormgeving',
                'features' => [
                    'CMS (zelf beheren)',
                    'Contact- / leadformulieren',
                    'SEO-basis',
                    'Blog / nieuws',
                    'Webshop / betalingen',
                    'Meertalig',
                ],
                'content' => [
                    'Ik wil hulp bij teksten',
                ],
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
