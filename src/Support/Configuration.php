<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

final readonly class Configuration
{
    public function __construct(
        public Endpoints $endpoints,
        public Credentials $credentials,
        public Transport $transport,
    ) {
    }

    public static function make(
        ?Endpoints $endpoints = null,
        ?Credentials $credentials = null,
        ?Transport $transport = null,
    ): self {
        return new self(
            endpoints: $endpoints ?? new Endpoints(),
            credentials: $credentials ?? new Credentials(),
            transport: $transport ?? new Transport(),
        );
    }
}
