<?php

namespace App\Services\Domains;

readonly class DomainCheckResult
{
    public function __construct(
        public string $domain,
        public bool $available,
        public string $status,
        public ?string $tld = null,
        public array $actions = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'domain' => $this->domain,
            'available' => $this->available,
            'status' => $this->status,
            'tld' => $this->tld,
            'actions' => $this->actions,
        ];
    }
}
