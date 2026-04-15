<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards\Dto;

final readonly class CardHolderData
{
    public function __construct(
        public string $name,
        public string $taxId,
    ) {
        if ($this->name === '' || $this->taxId === '') {
            throw new \InvalidArgumentException('The card holder name and tax id cannot be empty.');
        }
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
