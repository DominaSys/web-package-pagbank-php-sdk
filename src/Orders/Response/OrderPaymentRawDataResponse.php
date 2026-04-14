<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderPaymentRawDataResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function authorizationCode(): ?string
    {
        return $this->stringValue('authorization_code');
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

    public function securityLevelIndicator(): ?string
    {
        return $this->stringValue('security_level_indicator');
    }
}
