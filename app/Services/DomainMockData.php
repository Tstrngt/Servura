<?php

namespace App\Services;

/**
 * Temporary mock data for the domain management prototype.
 * All data here is placeholder content so the UI can be reviewed
 * before every backend integration is fully wired to TransIP / DirectAdmin.
 */
class DomainMockData
{
    public static function overview(string $domainName): array
    {
        return [
            'domain_name' => $domainName,
            'status' => 'Actief',
            'status_color' => 'emerald',
            'provider' => 'TransIP',
            'registration_date' => '29-09-2026',
            'expiry_date' => '29-09-2027',
            'auto_renew' => true,
            'registrar_lock' => true,
            'hosting_package' => 'Webhosting Plus',
            'hosting_status' => 'actief',
            'nameserver_status' => 'TransIP nameservers',
            'dns_status' => 'TransIP DNS',
        ];
    }

    public static function holder(): array
    {
        return [
            'type' => 'Registrant',
            'first_name' => 'Jan',
            'last_name' => 'Jansen',
            'company_name' => 'Jansen Webdesign B.V.',
            'street' => 'Dorpsstraat',
            'number' => '12',
            'postal_code' => '1234 AB',
            'city' => 'Amsterdam',
            'country' => 'NL',
            'email' => 'jan@jansenwebdesign.nl',
            'phone_number' => '+31 6 12345678',
        ];
    }

    public static function nameservers(): array
    {
        return [
            ['hostname' => 'ns0.transip.net', 'ipv4' => null, 'ipv6' => null],
            ['hostname' => 'ns1.transip.nl', 'ipv4' => null, 'ipv6' => null],
            ['hostname' => 'ns2.transip.eu', 'ipv4' => null, 'ipv6' => null],
        ];
    }

    public static function dnsRecords(): array
    {
        return [
            ['type' => 'A', 'name' => '@', 'content' => '91.99.74.51', 'expire' => 300],
            ['type' => 'CNAME', 'name' => 'www', 'content' => 'servura-test.nl', 'expire' => 300],
            ['type' => 'MX', 'name' => '@', 'content' => 'mail.servura.nl', 'expire' => 3600],
            ['type' => 'TXT', 'name' => '@', 'content' => 'v=spf1 include:_spf.servura.nl ~all', 'expire' => 3600],
        ];
    }

    public static function hosting(): array
    {
        return [
            'linked' => true,
            'package' => 'Webhosting Plus',
            'status' => 'actief',
            'server' => 'web01.servura.nl',
            'main_domain' => 'servura-test.nl',
        ];
    }

    public static function forwarding(): array
    {
        return [
            'enabled' => false,
            'type' => '301',
            'target' => 'https://voorbeeld.nl',
        ];
    }

    public static function transferToken(): array
    {
        return [
            'token' => 'ABC123XYZ789',
            'visible' => false,
        ];
    }
}
