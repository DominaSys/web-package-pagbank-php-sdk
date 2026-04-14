<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeAmountData
{
    public function __construct(
        public int $value,
        public string $currency = 'BRL',
    ) {
        if ($this->value <= 0 || $this->currency === '') {
            throw new \InvalidArgumentException('Charge amount requires a positive value and currency.');
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
