<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

use Dominasys\PagBank\Environment;

final readonly class Endpoints
{
    public function __construct(
        public Environment $environment = Environment::Sandbox,
        public ?string $apiBaseUri = null,
        public ?string $connectBaseUri = null,
        public ?string $sdkBaseUri = null,
    ) {}

    public function sdkBaseUri(): string
    {
        return $this->sdkBaseUri ?? ($this->environment === Environment::Sandbox
            ? 'https://sandbox.sdk.pagseguro.com' : 'https://sdk.pagseguro.com');
    }

    public function apiBaseUri(): string
    {
        return $this->apiBaseUri ?? $this->environment->apiBaseUri();
    }

    public function connectBaseUri(): string
    {
        return $this->connectBaseUri ?? $this->environment->connectBaseUri();
    }
}
