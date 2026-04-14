<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderQrCodeAmountData
{
    public function __construct(
        public int $value,
    ) {
        if ($this->value <= 0) {
            throw new \InvalidArgumentException('The QR code amount value must be greater than zero.');
        }
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return ['value' => $this->value];
    }
}
