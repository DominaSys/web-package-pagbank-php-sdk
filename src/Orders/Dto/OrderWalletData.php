<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

use Dominasys\PagBank\Orders\Enums\OrderWalletType;

final readonly class OrderWalletData
{
    public function __construct(
        public OrderWalletType $type,
        public string $key,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'key' => $this->key,
        ];
    }
}
