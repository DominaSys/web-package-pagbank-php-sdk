<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargePaymentRawDataResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function authorizationCode(): ?int
    {
        return $this->intValue('authorization_code');
    }

    public function nsu(): ?string
    {
        return $this->stringValue('nsu');
    }

    public function reasonCode(): ?string
    {
        return $this->stringValue('reason_code');
    }

    public function merchantAdviceCode(): ?string
    {
        return $this->stringValue('merchant_advice_code');
    }
}
