<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

use InvalidArgumentException;

final readonly class OrderBuyerInterestData
{
    public function __construct(public int $total, public int $installments)
    {
        if ($total < 0 || $installments < 1 || $installments > 12) {
            throw new InvalidArgumentException('Invalid buyer interest.');
        }
    }

    /** @return array{total: int, installments: int} */
    public function toArray(): array
    {
        return ['total' => $this->total, 'installments' => $this->installments];
    }
}
