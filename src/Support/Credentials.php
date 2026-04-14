<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

final readonly class Credentials
{
    public function __construct(
        public ?string $bearerToken = null,
        public ?string $clientId = null,
        public ?string $clientSecret = null,
    ) {
    }
}
