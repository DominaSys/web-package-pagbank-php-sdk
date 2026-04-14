<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargeCardResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function encrypted(): ?string
    {
        return $this->stringValue('encrypted');
    }

    public function number(): ?string
    {
        return $this->stringValue('number');
    }

    public function networkToken(): ?string
    {
        return $this->stringValue('network_token');
    }

    public function expMonth(): ?int
    {
        return $this->intValue('exp_month');
    }

    public function expYear(): ?int
    {
        return $this->intValue('exp_year');
    }

    public function securityCode(): ?string
    {
        return $this->stringValue('security_code');
    }

    public function store(): ?bool
    {
        return $this->boolValue('store');
    }

    public function brand(): ?string
    {
        return $this->stringValue('brand');
    }

    public function product(): ?string
    {
        return $this->stringValue('product');
    }

    public function firstDigits(): ?int
    {
        return $this->intValue('first_digits');
    }

    public function lastDigits(): ?int
    {
        return $this->intValue('last_digits');
    }

    public function holder(): ?ChargeCardHolderResponse
    {
        $payload = $this->nestedPayload('holder');

        return $payload !== null ? ChargeCardHolderResponse::fromArray($payload) : null;
    }

}
