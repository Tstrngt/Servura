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
     * @return array<int, array<string, mixed>>
     */
    public function tlds(): array;
}
