<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Dto;

use Dominasys\PagBank\Accounts\Enums\AccountPhoneType;

final readonly class AccountPhoneData
{
    public function __construct(
        public string $country,
        public string $area,
        public string $number,
        public ?AccountPhoneType $type = null,
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

        if ($this->type instanceof AccountPhoneType) {
            $payload['type'] = $this->type->value;
        }

        return $payload;
    }
}
