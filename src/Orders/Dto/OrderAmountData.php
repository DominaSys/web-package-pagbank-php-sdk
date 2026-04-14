<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderAmountData
{
    public function __construct(
        public int $value,
        public string $currency = 'BRL',
    ) {
        if ($this->value <= 0 || $this->currency === '') {
            throw new \InvalidArgumentException('Order amount requires a positive value and currency.');
        }
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'currency' => $this->currency,
        ];
    }
}
