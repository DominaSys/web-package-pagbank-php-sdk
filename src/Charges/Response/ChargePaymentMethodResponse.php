<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

use Dominasys\PagBank\Cards\Response\CardResponse;

final class ChargePaymentMethodResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function type(): ?string
    {
        return $this->stringValue('type');
    }

    public function installments(): ?int
    {
        return $this->intValue('installments');
    }

    public function capture(): ?bool
    {
        return $this->boolValue('capture');
    }

    public function captureBefore(): ?string
    {
        return $this->stringValue('capture_before');
    }

    public function softDescriptor(): ?string
    {
        return $this->stringValue('soft_descriptor');
    }

    public function card(): ?CardResponse
    {
        $payload = $this->nestedPayload('card');

        return $payload !== null ? CardResponse::fromArray($payload) : null;
    }

    public function tokenData(): ?ChargeTokenDataResponse
    {
        $payload = $this->nestedPayload('token_data');

        return $payload !== null ? ChargeTokenDataResponse::fromArray($payload) : null;
    }

    public function authenticationMethod(): ?ChargeAuthenticationMethodResponse
    {
        $payload = $this->nestedPayload('authentication_method');

        return $payload !== null ? ChargeAuthenticationMethodResponse::fromArray($payload) : null;
    }

    public function boleto(): ?ChargeBoletoResponse
    {
        $payload = $this->nestedPayload('boleto');

        return $payload !== null ? ChargeBoletoResponse::fromArray($payload) : null;
    }
}
