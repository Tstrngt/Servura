<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Standaard TLD's voor de publieke domeinchecker
    |--------------------------------------------------------------------------
    |
    | Wanneer een bezoeker een naam zonder TLD invoert, worden deze extensies
    | gecontroleerd op beschikbaarheid.
    |
    */

    'default_tlds' => ['nl', 'com', 'eu', 'net', 'be'],

    /*
    |--------------------------------------------------------------------------
    | Maximale lengte van een domeinnaam (zonder extensie)
    |--------------------------------------------------------------------------
    */

    'max_name_length' => 63,
];
