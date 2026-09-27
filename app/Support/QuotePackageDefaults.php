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
        return match ($service->slug) {
            'starter', 'starter-website' => [
                'goal' => 'Visitekaartje / online brochure',
                'pages' => "1 - 5 pagina's",
                'visitors' => 'Minder dan 1.000',
                'design' => 'Ik heb al een huisstijl / logo',
                'features' => [
                    'CMS (zelf beheren)',
                    'Contact- / leadformulieren',
                ],
                'content' => [
                    'Ik lever teksten en beelden zelf aan',
                ],
            ],
            'business', 'business-website' => [
                'goal' => 'Meer leads en aanvragen',
                'pages' => "6 - 10 pagina's",
                'visitors' => '1.000 - 5.000',
                'design' => 'Ik wil voorbeelden en advies',
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
            'pro', 'pro-website' => [
                'goal' => 'Producten of diensten verkopen',
                'pages' => "11 - 20 pagina's",
                'visitors' => '5.000 - 25.000',
                'design' => 'Ik wil een nieuw logo en huisstijl',
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
