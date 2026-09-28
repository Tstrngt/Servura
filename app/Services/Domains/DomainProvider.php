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
}
