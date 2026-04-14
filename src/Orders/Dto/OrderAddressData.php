<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderAddressData
{
    public function __construct(
        public string $street,
        public string $number,
        public ?string $complement,
        public string $locality,
        public string $city,
        public string $regionCode,
        public string $country,
        public string $postalCode,
        public ?string $region = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $payload = [
            'street' => $this->street,
            'number' => $this->number,
            'locality' => $this->locality,
            'city' => $this->city,
            'region_code' => $this->regionCode,
            'country' => $this->country,
            'postal_code' => $this->postalCode,
        ];

        if ($this->complement !== null && $this->complement !== '') {
            $payload['complement'] = $this->complement;
        }

        if ($this->region !== null && $this->region !== '') {
            $payload['region'] = $this->region;
        }

        return $payload;
    }
}
