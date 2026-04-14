<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderCardResponse extends OrderResponseNode
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

    public function holder(): ?OrderCardHolderResponse
    {
        $payload = $this->nestedPayload('holder');

        return $payload !== null ? OrderCardHolderResponse::fromArray($payload) : null;
    }

    public function wallet(): ?OrderWalletResponse
    {
        $payload = $this->nestedPayload('wallet');

        return $payload !== null ? OrderWalletResponse::fromArray($payload) : null;
    }

    public function tokenData(): ?OrderTokenDataResponse
    {
        $payload = $this->nestedPayload('token_data');

        return $payload !== null ? OrderTokenDataResponse::fromArray($payload) : null;
    }

    public function authenticationMethod(): ?OrderAuthenticationMethodResponse
    {
        $payload = $this->nestedPayload('authentication_method');

        return $payload !== null ? OrderAuthenticationMethodResponse::fromArray($payload) : null;
    }
}
