<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderCustomerData
{
    /**
     * @param  array<int, OrderPhoneData>  $phones
     */
    public function __construct(
        public string $taxId,
        public ?string $name = null,
        public ?string $email = null,
        public array $phones = [],
    ) {
        if ($this->taxId === '') {
            throw new \InvalidArgumentException('The customer tax_id cannot be empty.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'tax_id' => $this->taxId,
        ];

        if ($this->name !== null && $this->name !== '') {
            $payload['name'] = $this->name;
        }

        if ($this->email !== null && $this->email !== '') {
            $payload['email'] = $this->email;
        }

        if ($this->phones !== []) {
            $payload['phones'] = array_map(
                static fn (OrderPhoneData $phone): array => $phone->toArray(),
                $this->phones,
            );
        }

        return $payload;
    }
}
