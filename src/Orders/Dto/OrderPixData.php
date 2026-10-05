<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderPixData
{
    public function __construct(public string $expirationDate) {}

    /** @return array{expiration_date: string} */
    public function toArray(): array
    {
        return ['expiration_date' => $this->expirationDate];
    }
}
