<?php

namespace App\Support;

use App\Models\BillingSetting;

/**
 * Configurable option lists for the public quote-builder form.
 * Admins edit these under Instellingen > Offerteformulier.
 */
class QuoteFormFields
{
    public const GROUPS = [
        'goals' => 'Doel van de website (radioknoppen, verplicht)',
        'pages' => "Aantal pagina's (dropdown, verplicht)",
        'visitors' => 'Verwachte bezoekers per maand (dropdown, verplicht)',
        'design' => 'Ontwerp & huisstijl (dropdown, verplicht)',
        'features' => 'Functionaliteiten (checkboxen)',
        'content' => 'Content & teksten (checkboxen)',
        'timeline' => 'Gewenste oplevering (dropdown, verplicht)',
        'budget' => 'Budgetindicatie (dropdown, optioneel)',
    ];

    public static function defaults(): array
    {
        return [
            'goals' => [
                ['label' => 'Visitekaartje / online brochure', 'note' => 'Informatie over uw bedrijf tonen'],
                ['label' => 'Meer leads en aanvragen', 'note' => 'Bezoekers omzetten in contactaanvragen'],
                ['label' => 'Producten of diensten verkopen', 'note' => 'Webshop of boekingssysteem'],
                ['label' => 'Service richting klanten', 'note' => 'Klantenportaal of informatiehub'],
            ],
            'pages' => [
                ['label' => "1 - 5 pagina's", 'note' => ''],
                ['label' => "6 - 10 pagina's", 'note' => ''],
                ['label' => "11 - 20 pagina's", 'note' => ''],
                ['label' => "21 - 50 pagina's", 'note' => ''],
                ['label' => 'Meer dan 50 pagina\'s', 'note' => ''],
            ],
            'visitors' => [
                ['label' => 'Minder dan 1.000', 'note' => ''],
                ['label' => '1.000 - 5.000', 'note' => ''],
                ['label' => '5.000 - 25.000', 'note' => ''],
                ['label' => 'Meer dan 25.000', 'note' => ''],
            ],
            'design' => [
                ['label' => 'Ik heb al een huisstijl / logo', 'note' => ''],
                ['label' => 'Ik wil een nieuw logo en huisstijl', 'note' => ''],
                ['label' => 'Ik wil voorbeelden en advies', 'note' => ''],
            ],
            'features' => [
                ['label' => 'CMS (zelf beheren)', 'note' => 'Inbegrepen bij de meeste websites'],
                ['label' => 'Blog / nieuws', 'note' => 'Vaak € 300 - € 800 extra'],
                ['label' => 'Contact- / leadformulieren', 'note' => 'Standaard bij de meeste pakketten'],
                ['label' => 'SEO-basis', 'note' => 'Vaak € 500 - € 1.500'],
                ['label' => 'Webshop / betalingen', 'note' => 'Vanaf € 5.000 bij de meeste bureaus'],
                ['label' => 'Meertalig', 'note' => 'Vaak € 500 - € 1.500 per taal'],
                ['label' => 'Koppeling CRM / ERP', 'note' => 'Vaak vanaf € 1.000'],
                ['label' => 'Klantenportaal / login', 'note' => 'Vaak € 2.000 - € 5.000'],
                ['label' => 'Afspraken systeem', 'note' => 'Vaak € 750 - € 2.000'],
            ],
            'content' => [
                ['label' => 'Ik lever teksten en beelden zelf aan', 'note' => ''],
                ['label' => 'Ik wil hulp bij teksten', 'note' => ''],
                ['label' => 'Ik wil fotografie / beelden', 'note' => ''],
            ],
            'timeline' => [
                ['label' => 'Zo snel mogelijk', 'note' => ''],
                ['label' => 'Binnen 1 - 2 maanden', 'note' => ''],
                ['label' => 'Binnen 3 - 6 maanden', 'note' => ''],
                ['label' => 'Geen haast', 'note' => ''],
            ],
            'budget' => [
                ['label' => 'Minder dan € 2.500', 'note' => ''],
                ['label' => '€ 2.500 - € 5.000', 'note' => ''],
                ['label' => '€ 5.000 - € 10.000', 'note' => ''],
                ['label' => '€ 10.000 - € 25.000', 'note' => ''],
                ['label' => 'Meer dan € 25.000', 'note' => ''],
            ],
        ];
    }

    /**
     * Resolve the effective option lists: stored settings merged over defaults.
     */
    public static function resolve(): array
    {
        $defaults = self::defaults();

        try {
            $stored = json_decode(BillingSetting::valueFor('quote_form_fields', ''), true);
        } catch (\Throwable) {
            $stored = null;
        }

        if (! is_array($stored)) {
            return $defaults;
        }

        foreach (self::GROUPS as $key => $label) {
            if (isset($stored[$key]) && is_array($stored[$key]) && count($stored[$key]) > 0) {
                $defaults[$key] = array_values(array_map(fn ($o) => [
                    'label' => (string) ($o['label'] ?? ''),
                    'note' => (string) ($o['note'] ?? ''),
                ], array_filter($stored[$key], fn ($o) => ($o['label'] ?? '') !== '')));
            }
        }

        return $defaults;
    }

    /**
     * Parse textarea input ("Label" or "Label | toelichting" per line) into options.
     */
    public static function parseLines(string $input): array
    {
        $options = [];
        foreach (preg_split('/\r?\n/', $input) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            [$label, $note] = array_pad(explode('|', $line, 2), 2, '');
            $options[] = ['label' => trim($label), 'note' => trim($note)];
        }

        return $options;
    }

    /**
     * Serialize options back to textarea lines.
     */
    public static function toLines(array $options): string
    {
        return collect($options)
            ->map(fn ($o) => $o['label'].($o['note'] !== '' ? ' | '.$o['note'] : ''))
            ->implode("\n");
    }
}
