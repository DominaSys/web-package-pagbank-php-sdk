<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

use Dominasys\PagBank\Orders\Enums\OrderCustomerPhoneType;

final readonly class OrderPhoneData
{
    public function __construct(
        public string $country,
        public string $area,
        public string $number,
        public ?OrderCustomerPhoneType $type = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $payload = [
            'country' => $this->country,
            'area' => $this->area,
            'number' => $this->number,
        ];

        if ($this->type instanceof OrderCustomerPhoneType) {
            $payload['type'] = $this->type->value;
        }

        return $payload;
    }
}
