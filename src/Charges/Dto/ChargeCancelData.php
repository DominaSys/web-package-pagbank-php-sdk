<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelData
{
    public function __construct(
        public ChargeAmountData $amount,
        public ?ChargeCancelSplitsData $splits = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'amount' => $this->amount->toArray(),
        ];

        if ($this->splits instanceof ChargeCancelSplitsData) {
            $payload['splits'] = $this->splits->toArray();
        }

        return $payload;
    }
}
