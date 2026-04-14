<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Response;

final class AccountAddressResponse extends AccountResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function street(): ?string
    {
        return $this->stringValue('street');
    }

    public function number(): ?string
    {
        return $this->stringValue('number');
    }

    public function complement(): ?string
    {
        return $this->stringValue('complement');
    }

    public function locality(): ?string
    {
        return $this->stringValue('locality');
    }

    public function city(): ?string
    {
        return $this->stringValue('city');
    }

    public function regionCode(): ?string
    {
        return $this->stringValue('region_code');
    }

    public function country(): ?string
    {
        return $this->stringValue('country');
    }

    public function postalCode(): ?string
    {
        return $this->stringValue('postal_code');
    }
}
