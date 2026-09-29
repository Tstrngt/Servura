<?php

namespace App\Services\Domains;

interface DomainProvider
{
    public function name(): string;

    public function isConfigured(): bool;

    public function isReadOnly(): bool;

    /**
     * Test the connection and return a human-friendly status.
     *
     * @return array{success: bool, message: string}
     */
    public function testConnection(): array;

    public function checkAvailability(string $domain): DomainCheckResult;

    /**
     * @return DomainCheckResult[]
     */
    public function suggest(string $name, array $tlds = []): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function tlds(): array;

    /**
     * Register a domain name with the provider.
     *
     * @param  array<int, mixed>  $contacts
     * @param  array<int, mixed>  $nameservers
     */
    public function registerDomain(string $domain, array $contacts = [], array $nameservers = []): void;

    /**
     * Transfer a domain name to the provider.
     *
     * @param  array<int, mixed>  $contacts
     * @param  array<int, mixed>  $nameservers
     */
    public function transferDomain(string $domain, string $authCode, array $contacts = [], array $nameservers = []): void;

    /**
     * Fetch domain details from the provider.
     *
     * @return array<string, mixed>
     */
    public function getDomainInfo(string $domain): array;

    /**
     * @return string[]
     */
    public function getNameservers(string $domain): array;

    /**
     * @param string[] $nameservers
     */
    public function setNameservers(string $domain, array $nameservers): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getContacts(string $domain): array;

    /**
     * @param array<int, mixed> $contacts
     */
    public function setContacts(string $domain, array $contacts): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDnsEntries(string $domain): array;

    /**
     * @param array<int, mixed> $dnsEntries
     */
    public function setDnsEntries(string $domain, array $dnsEntries): void;

    /**
     * Get the current authcode for a domain, if supported.
     */
    public function getAuthCode(string $domain): ?string;

    /**
     * Request a new authcode for a domain.
     */
    public function requestAuthCode(string $domain): void;

    /**
     * Get TLD capabilities for a given extension.
     *
     * @return array<string, mixed>
     */
    public function getTldCapabilities(string $tld): array;
}
