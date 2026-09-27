<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Centrale bedrijfsgegevens
    |--------------------------------------------------------------------------
    |
    | Deze waarden worden gebruikt op de footer, contactpagina, juridische
    | pagina's en in communicatie. Vul ontbrekende velden in via het .env-bestand
    | of de algemene instellingen in het admin-paneel.
    |
    */

    'legal_name' => env('COMPANY_LEGAL_NAME', '[Juridische bedrijfsnaam nog in te vullen]'),
    'trade_name' => env('COMPANY_TRADE_NAME', 'Servura'),

    'address' => env('COMPANY_ADDRESS', '[Adres nog in te vullen]'),
    'postal_code' => env('COMPANY_POSTAL_CODE', '[Postcode nog in te vullen]'),
    'city' => env('COMPANY_CITY', '[Plaats nog in te vullen]'),
    'country' => env('COMPANY_COUNTRY', 'Nederland'),

    'kvk_number' => env('COMPANY_KVK', '[KvK-nummer nog in te vullen]'),
    'vat_number' => env('COMPANY_VAT', '[Btw-nummer nog in te vullen]'),

    'email' => env('COMPANY_EMAIL', config('mail.from.address', 'info@servura.nl')),
    'phone' => env('COMPANY_PHONE', '[Telefoonnummer nog in te vullen]'),
    'website' => env('COMPANY_WEBSITE', 'https://servura.nl'),

    'privacy_email' => env('COMPANY_PRIVACY_EMAIL', '[Privacycontact nog in te vullen]'),
    'abuse_email' => env('COMPANY_ABUSE_EMAIL', '[Abuse e-mailadres nog in te vullen]'),
];
