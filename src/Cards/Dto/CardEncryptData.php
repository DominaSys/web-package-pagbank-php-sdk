<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards\Dto;

final readonly class CardEncryptData
{
    public function __construct(
        public string $publicKey,
        public string $number,
        public int $expMonth,
        public int $expYear,
        public string $holder,
        public ?string $securityCode = null,
    ) {
    }
}
