<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderBoletoHolderData
{
    public function __construct(
        public string $name,
        public string $taxId,
        public ?string $email = null,
        public ?OrderAddressData $address = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'name' => $this->name,
            'tax_id' => $this->taxId,
        ];

        if ($this->email !== null && $this->email !== '') {
            $payload['email'] = $this->email;
        }

        if ($this->address instanceof OrderAddressData) {
            $payload['address'] = $this->address->toArray();
        }

        return $payload;
    }
}
