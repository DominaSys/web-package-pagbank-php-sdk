<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCaptureData
{
    public function __construct(
        public ChargeAmountData $amount,
    ) {
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount->toArray(),
        ];
    }
}
