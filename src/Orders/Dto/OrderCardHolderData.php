<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderCardHolderData
{
    public function __construct(
        public string $name,
        public string $taxId,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'tax_id' => $this->taxId,
        ];
    }
}
