<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Centrale prijsconfiguratie voor websitepakketten en offerte-extras
    |--------------------------------------------------------------------------
    |
    | Alle prijzen op de site, in pakketpopups en in het offerteformulier
    | worden uit deze configuratie gelezen. Zo hoeven toekomstige
    | prijswijzigingen niet op meerdere plekken te worden doorgevoerd.
    |
    */

    'packages' => [
        'starter' => [
            'title' => 'Starter Website',
            'basePrice' => 749,
            'includedPagesLabel' => "tot 4 pagina's",
            'designDefault' => 'Ik heb een logo en kleuren',
        ],
        'starter-website' => [
            'title' => 'Starter Website',
            'basePrice' => 749,
            'includedPagesLabel' => "tot 4 pagina's",
            'designDefault' => 'Ik heb een logo en kleuren',
        ],
        'business' => [
            'title' => 'Business Website',
            'basePrice' => 1099,
            'includedPagesLabel' => "tot 7 pagina's",
            'designDefault' => 'Ik heb een complete huisstijl',
        ],
        'business-website' => [
            'title' => 'Business Website',
            'basePrice' => 1099,
            'includedPagesLabel' => "tot 7 pagina's",
            'designDefault' => 'Ik heb een complete huisstijl',
        ],
        'pro' => [
            'title' => 'Pro Website',
            'basePrice' => 1495,
            'includedPagesLabel' => "tot 10 pagina's",
            'designDefault' => 'Ik wil hulp bij de vormgeving',
        ],
        'pro-website' => [
            'title' => 'Pro Website',
            'basePrice' => 1495,
            'includedPagesLabel' => "tot 10 pagina's",
            'designDefault' => 'Ik wil hulp bij de vormgeving',
        ],
    ],

    'extras' => [
        'extraPage' => 75,
        'blog' => 100,
        'multilingualPerLanguage' => 150,
        'webshopFrom' => 500,
        'crmErpFrom' => 300,
        'customerPortalFrom' => 500,
        'appointmentSystemFrom' => 250,
        'copywritingPerPageFrom' => 75,
        'imageSelectionFrom' => 75,
        'designHelpFrom' => 250,
        'rushDeliveryFrom' => 150,
    ],
];
