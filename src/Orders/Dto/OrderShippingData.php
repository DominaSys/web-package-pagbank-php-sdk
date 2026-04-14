<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderShippingData
{
    public function __construct(
        public OrderAddressData $address,
    ) {
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function toArray(): array
    {
        return [
            'address' => $this->address->toArray(),
        ];
    }
}
