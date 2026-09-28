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
        'content' => 'Content & teksten (radioknoppen, 1 keuze)',
        'timeline' => 'Gewenste oplevering (dropdown, verplicht)',
        'budget' => 'Budgetindicatie (dropdown, optioneel)',
    ];

    public static function defaults(): array
    {
        $extras = config('pricing.extras', []);

        return [
            'goals' => [
                ['label' => 'Visitekaartje / online brochure', 'note' => 'Informatie over uw bedrijf tonen'],
                ['label' => 'Meer leads en aanvragen', 'note' => 'Bezoekers omzetten in contactaanvragen'],
                ['label' => 'Producten of diensten verkopen', 'note' => 'Webshop of boekingssysteem'],
                ['label' => 'Service richting klanten', 'note' => 'Klantenportaal of informatiehub'],
            ],
            'pages' => [
                ['label' => "tot 4 pagina's", 'note' => ''],
                ['label' => "tot 7 pagina's", 'note' => ''],
                ['label' => "tot 10 pagina's", 'note' => ''],
                ['label' => "meer dan 10 pagina's", 'note' => ''],
            ],
            'visitors' => [
                ['label' => 'Minder dan 1.000', 'note' => ''],
                ['label' => '1.000 - 5.000', 'note' => ''],
                ['label' => '5.000 - 25.000', 'note' => ''],
                ['label' => 'Meer dan 25.000', 'note' => ''],
            ],
            'design' => [
                ['label' => 'Ik heb een complete huisstijl', 'note' => ''],
                ['label' => 'Ik heb een logo en kleuren', 'note' => ''],
                ['label' => 'Ik wil hulp bij de vormgeving', 'note' => 'Vanaf + €'.($extras['designHelpFrom'] ?? 250).' / prijs op aanvraag'],
                ['label' => 'Ik heb nog geen huisstijl', 'note' => ''],
            ],
            'features' => [
                ['label' => 'CMS (zelf beheren)', 'note' => 'Inbegrepen bij alle websitepakketten', 'info' => 'Een gebruiksvriendelijk systeem waarmee u zelf pagina\'s, teksten en afbeeldingen kunt beheren.'],
                ['label' => 'Blog / nieuws', 'note' => 'Vanaf + €'.($extras['blog'] ?? 100), 'info' => 'Een nieuwssectie om regelmatig artikelen, updates en kennisberichten te publiceren.'],
                ['label' => 'Contact- / leadformulieren', 'note' => 'Inbegrepen bij alle websitepakketten', 'info' => 'Formulieren voor aanvragen en contact, gekoppeld aan uw mailbox en/of klantsysteem.'],
                ['label' => 'SEO-basis', 'note' => 'Inbegrepen bij alle websitepakketten', 'info' => 'Technische en inhoudelijke basisoptimalisatie voor betere vindbaarheid in zoekmachines.'],
                ['label' => 'Analytics', 'note' => 'Inbegrepen bij Pro', 'info' => 'Inzicht in bezoekersgedrag via privacyvriendelijke analytics.'],
                ['label' => 'Portfolio / projectenmodule', 'note' => 'Inbegrepen bij Pro', 'info' => 'Toon projecten, portfolio-items of referenties op een overzichtelijke manier.'],
                ['label' => 'Webshop / betalingen', 'note' => 'Vanaf + €'.($extras['webshopFrom'] ?? 500), 'info' => 'Productcatalogus, winkelwagen en betalingen via Mollie.'],
                ['label' => 'Meertalig', 'note' => '+ €'.($extras['multilingualPerLanguage'] ?? 150).' per taal', 'info' => 'Website beschikbaar in meerdere talen, inclusief taalwisselaar.'],
                ['label' => 'Koppeling CRM / ERP', 'note' => 'Vanaf + €'.($extras['crmErpFrom'] ?? 300), 'info' => 'Gegevensuitwisseling met uw bestaande klant- of bedrijfssysteem.'],
                ['label' => 'Klantenportaal / login', 'note' => 'Vanaf + €'.($extras['customerPortalFrom'] ?? 500), 'info' => 'Afgeschermde omgeving waarin klanten documenten of gegevens kunnen inzien.'],
                ['label' => 'Afspraken systeem', 'note' => 'Vanaf + €'.($extras['appointmentSystemFrom'] ?? 250), 'info' => 'Online afspraken inplannen, bevestigen en herinneringen versturen.'],
            ],
            'content' => [
                ['label' => 'Ik lever teksten en beelden zelf aan', 'note' => 'Geen meerprijs'],
                ['label' => 'Ik wil hulp bij teksten', 'note' => 'Vanaf + €'.($extras['copywritingPerPageFrom'] ?? 75).' per pagina'],
                ['label' => 'Ik wil hulp met fotografie / beelden', 'note' => 'Prijs op aanvraag'],
            ],
            'timeline' => [
                ['label' => 'Zo snel mogelijk', 'note' => ''],
                ['label' => 'Binnen 1 - 2 maanden', 'note' => ''],
                ['label' => 'Binnen 3 - 6 maanden', 'note' => ''],
                ['label' => 'Geen haast', 'note' => ''],
            ],
            'budget' => [
                ['label' => 'Tot € 250', 'note' => ''],
                ['label' => '€ 250 - € 500', 'note' => ''],
                ['label' => '€ 500 - € 750', 'note' => ''],
                ['label' => '€ 750 - € 1.000', 'note' => ''],
                ['label' => '€ 1.000 - € 1.500', 'note' => ''],
                ['label' => '€ 1.500 - € 2.500', 'note' => ''],
                ['label' => 'Meer dan € 2.500', 'note' => ''],
                ['label' => 'Nog niet bepaald', 'note' => ''],
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
                $defaultOptions = collect($defaults[$key] ?? [])->keyBy('label');

                $defaults[$key] = array_values(array_map(function ($o) use ($defaultOptions) {
                    $label = (string) ($o['label'] ?? '');
                    $default = $defaultOptions->get($label);

                    return [
                        'label' => $label,
                        'note' => (string) ($o['note'] ?? $default['note'] ?? ''),
                        'info' => (string) ($o['info'] ?? $default['info'] ?? ''),
                    ];
                }, array_filter($stored[$key], fn ($o) => ($o['label'] ?? '') !== '')));
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
            $parts = array_pad(explode('|', $line, 3), 3, '');
            $options[] = [
                'label' => trim($parts[0]),
                'note' => trim($parts[1]),
                'info' => trim($parts[2]),
            ];
        }

        return $options;
    }

    /**
     * Serialize options back to textarea lines.
     */
    public static function toLines(array $options): string
    {
        return collect($options)
            ->map(function ($o) {
                $line = $o['label'];
                if (($o['note'] ?? '') !== '') {
                    $line .= ' | '.$o['note'];
                }
                if (($o['info'] ?? '') !== '') {
                    $line .= ' | '.$o['info'];
                }

                return $line;
            })
            ->implode("\n");
    }
}
