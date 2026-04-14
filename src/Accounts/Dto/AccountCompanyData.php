<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Dto;

final readonly class AccountCompanyData
{
    /**
     * @param  array<int, AccountPhoneData>  $phones
     */
    public function __construct(
        public string $name,
        public string $taxId,
        public ?AccountAddressData $address = null,
        public array $phones = [],
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

        if ($this->address instanceof AccountAddressData) {
            $payload['address'] = $this->address->toArray();
        }

        if ($this->phones !== []) {
            $payload['phones'] = array_map(
                static fn (AccountPhoneData $phone): array => $phone->toArray(),
                $this->phones,
            );
        }

        return $payload;
    }
}
