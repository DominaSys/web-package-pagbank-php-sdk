<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderPaymentData
{
    /**
     * @param  array<int, OrderChargeData>  $charges
     */
    public function __construct(
        public array $charges,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'charges' => array_map(
                static fn (OrderChargeData $charge): array => $charge->toArray(),
                $this->charges,
            ),
        ];
    }
}
