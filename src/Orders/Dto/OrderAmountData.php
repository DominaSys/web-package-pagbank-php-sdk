<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderAmountData
{
    public function __construct(
        public int $value,
        public string $currency = 'BRL',
        public ?OrderBuyerInterestData $buyerInterest = null,
    ) {
        if ($this->value <= 0 || $this->currency === '') {
            throw new \InvalidArgumentException('Order amount requires a positive value and currency.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'value' => $this->value,
            'currency' => $this->currency,
        ];
        if ($this->buyerInterest !== null) {
            $payload['fees']['buyer']['interest'] = $this->buyerInterest->toArray();
        }

        return $payload;
    }
}
