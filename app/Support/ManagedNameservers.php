<?php

namespace App\Support;

use App\Models\BillingSetting;
use Throwable;

/**
 * Bepaalt of een set nameservers "door ons" wordt beheerd, zodat DNS-records
 * via het portaal (de DNS-zone van de domeinprovider) aangepast kunnen worden.
 * Domeinen op andere nameservers - zoals de nameservers van een gekoppelde
 * webhostingserver (secundaire/externe DNS) - vallen hier buiten.
 */
class ManagedNameservers
{
    public const OPENPROVIDER_DEFAULTS = [
        'ns1.openprovider.nl',
        'ns1.openprovider.be',
        'ns1.openprovider.eu',
        'ns2.openprovider.nl',
        'ns2.openprovider.be',
        'ns2.openprovider.eu',
        'ns3.openprovider.nl',
        'ns3.openprovider.be',
        'ns3.openprovider.eu',
    ];

    /**
     * @return array<int, string>
     */
    public static function hosts(): array
    {
        try {
            $raw = BillingSetting::valueFor('domain_default_nameservers', BillingSetting::valueFor('transip_default_nameservers', ''));
        } catch (Throwable $e) {
            $raw = '';
        }

        $configured = array_map(
            fn ($host) => mb_strtolower(trim((string) $host)),
            array_filter(preg_split('/[\r\n,]+/', (string) $raw))
        );

        return array_values(array_unique(array_merge($configured, self::OPENPROVIDER_DEFAULTS)));
    }

    /**
     * True wanneer alle huidige nameservers in de beheerde set vallen.
     *
     * @param array<int, mixed> $nameservers Items zoals ['hostname' => 'ns1...'] of strings.
     */
    public static function check(array $nameservers): bool
    {
        $current = array_filter(array_map(
            fn ($ns) => mb_strtolower(trim((string) (is_array($ns) ? ($ns['hostname'] ?? $ns['name'] ?? '') : $ns))),
            $nameservers
        ));

        if (empty($current)) {
            return false;
        }

        return empty(array_diff($current, self::hosts()));
    }
}
