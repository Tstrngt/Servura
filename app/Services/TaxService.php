<?php

namespace App\Services;

use App\Models\BillingSetting;

class TaxService
{
    public const COUNTRY_RATES = [
        'AT' => 20.0, 'BE' => 21.0, 'BG' => 20.0, 'HR' => 25.0, 'CY' => 19.0,
        'CZ' => 21.0, 'DE' => 19.0, 'DK' => 25.0, 'EE' => 22.0, 'ES' => 21.0,
        'FI' => 25.5, 'FR' => 20.0, 'GR' => 24.0, 'HU' => 27.0, 'IE' => 23.0,
        'IT' => 22.0, 'LT' => 21.0, 'LU' => 17.0, 'LV' => 21.0, 'MT' => 18.0,
        'NL' => 21.0, 'PL' => 23.0, 'PT' => 23.0, 'RO' => 19.0, 'SE' => 25.0,
        'SI' => 22.0, 'SK' => 23.0,
    ];

    public const COUNTRIES = [
        'NL' => 'Nederland', 'BE' => 'België', 'DE' => 'Duitsland', 'FR' => 'Frankrijk',
        'ES' => 'Spanje', 'IT' => 'Italië', 'PT' => 'Portugal', 'AT' => 'Oostenrijk',
        'DK' => 'Denemarken', 'SE' => 'Zweden', 'FI' => 'Finland', 'IE' => 'Ierland',
        'LU' => 'Luxemburg', 'PL' => 'Polen', 'CZ' => 'Tsjechië', 'SK' => 'Slowakije',
        'GR' => 'Griekenland', 'HR' => 'Kroatië', 'SI' => 'Slovenië', 'HU' => 'Hongarije',
        'RO' => 'Roemenië', 'BG' => 'Bulgarije', 'EE' => 'Estland', 'LV' => 'Letland',
        'LT' => 'Litouwen', 'CY' => 'Cyprus', 'MT' => 'Malta', 'US' => 'Verenigde Staten',
        'GB' => 'Verenigd Koninkrijk', 'CH' => 'Zwitserland', 'NO' => 'Noorwegen',
    ];

    public function rateFor(string $countryCode, bool $validEuVatNumber = false): float
    {
        $countryCode = strtoupper($countryCode);
        $businessCountry = strtoupper((string) BillingSetting::valueFor('business_country', 'NL'));

        if (!BillingSetting::boolean('country_vat_enabled')) {
            return BillingSetting::decimal('default_vat_rate', 21.0);
        }

        if ($validEuVatNumber && $countryCode !== $businessCountry && isset(self::COUNTRY_RATES[$countryCode])) {
            return 0.0;
        }

        return self::COUNTRY_RATES[$countryCode] ?? 0.0;
    }

    public function calculate(float $subtotal, string $countryCode, bool $validEuVatNumber = false): array
    {
        $rate = $this->rateFor($countryCode, $validEuVatNumber);
        $amount = round($subtotal * ($rate / 100), 2);

        return ['rate' => $rate, 'amount' => $amount, 'total' => $subtotal + $amount];
    }

    public function ratesForCheckout(): array
    {
        if (!BillingSetting::boolean('country_vat_enabled')) {
            return array_fill_keys(array_keys(self::COUNTRIES), BillingSetting::decimal('default_vat_rate', 21.0));
        }

        return collect(self::COUNTRIES)->mapWithKeys(fn ($name, $code) => [$code => self::COUNTRY_RATES[$code] ?? 0.0])->all();
    }
}
