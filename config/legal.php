<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Juridische documentversies
    |--------------------------------------------------------------------------
    |
    | Gebruik deze versies bij het loggen van akkoordverklaringen en onderaan
    | juridische pagina's. Wijzig de versie en ingangsdatum wanneer de inhoud
    | juridisch is gecontroleerd of substantieel wijzigt.
    |
    */

    'versions' => [
        'terms' => [
            'version' => env('LEGAL_TERMS_VERSION', '1.0'),
            'effective_date' => env('LEGAL_TERMS_DATE', '[Ingangsdatum nog in te vullen]'),
        ],
        'privacy' => [
            'version' => env('LEGAL_PRIVACY_VERSION', '1.0'),
            'effective_date' => env('LEGAL_PRIVACY_DATE', '[Ingangsdatum nog in te vullen]'),
        ],
        'cookies' => [
            'version' => env('LEGAL_COOKIES_VERSION', '1.0'),
            'effective_date' => env('LEGAL_COOKIES_DATE', '[Ingangsdatum nog in te vullen]'),
        ],
        'hosting_terms' => [
            'version' => env('LEGAL_HOSTING_TERMS_VERSION', '1.0'),
            'effective_date' => env('LEGAL_HOSTING_TERMS_DATE', '[Ingangsdatum nog in te vullen]'),
        ],
        'acceptable_use' => [
            'version' => env('LEGAL_ACCEPTABLE_USE_VERSION', '1.0'),
            'effective_date' => env('LEGAL_ACCEPTABLE_USE_DATE', '[Ingangsdatum nog in te vullen]'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bewaartermijnen privacy
    |--------------------------------------------------------------------------
    |
    | null = nog niet bepaald. Templates tonen dan een placeholder/opsomming.
    |
    */

    'retention' => [
        'contact_request' => env('LEGAL_RETENTION_CONTACT', null),
        'quote_request' => env('LEGAL_RETENTION_QUOTE', null),
        'customer_account' => env('LEGAL_RETENTION_CUSTOMER', null),
        'analytics' => env('LEGAL_RETENTION_ANALYTICS', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Subprocessors / externe dienstverleners
    |--------------------------------------------------------------------------
    |
    | Voeg alleen partijen toe die daadwerkelijk worden gebruikt. Laat velden
    | leeg of gebruik placeholders zolang details niet bekend zijn.
    |
    */

    'subprocessors' => [
        // Voorbeeld:
        // [
        //     'name' => 'Hostingprovider B.V.',
        //     'purpose' => 'Webhosting en opslag van klantgegevens',
        //     'country' => 'Nederland',
        //     'privacy_url' => 'https://voorbeeld.nl/privacy',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cookies
    |--------------------------------------------------------------------------
    |
    | Voeg hier daadwerkelijk gebruikte cookies toe. De cookiemanager gebruikt
    | deze lijst voor de cookieverklaring en de categorisatie.
    |
    */

    'cookies' => [
        [
            'name' => 'XSRF-TOKEN',
            'provider' => config('company.trade_name', 'Servura'),
            'purpose' => 'Beveiliging: helpt bij het voorkomen van cross-site request forgery (CSRF) aanvallen.',
            'category' => 'necessary',
            'duration' => 'Sessie',
        ],
        [
            'name' => 'servura_session',
            'provider' => config('company.trade_name', 'Servura'),
            'purpose' => 'Sessiecookie voor ingelogde gebruikers en formulierstatus.',
            'category' => 'necessary',
            'duration' => 'Sessie',
        ],
        [
            'name' => 'cookie_consent',
            'provider' => config('company.trade_name', 'Servura'),
            'purpose' => 'Onthoudt de cookievoorkeuren van de bezoeker.',
            'category' => 'necessary',
            'duration' => '1 jaar',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hosting / SLA placeholders
    |--------------------------------------------------------------------------
    |
    | null = nog niet bepaald.
    |
    */

    'hosting' => [
        'backup_retention' => env('LEGAL_BACKUP_RETENTION', null),
        'backup_frequency' => env('LEGAL_BACKUP_FREQUENCY', null),
        'recovery_time' => env('LEGAL_RECOVERY_TIME', null),
        'uptime_sla' => env('LEGAL_UPTIME_SLA', null),
        'support_response_time' => env('LEGAL_SUPPORT_RESPONSE_TIME', null),
        'fair_use_storage' => env('LEGAL_FAIR_USE_STORAGE', null),
        'fair_use_traffic' => env('LEGAL_FAIR_USE_TRAFFIC', null),
    ],
];
