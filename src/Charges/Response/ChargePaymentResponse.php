<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargePaymentResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function code(): ?int
    {
        return $this->intValue('code');
    }

    public function message(): ?string
    {
        return $this->stringValue('message');
    }

    public function reference(): ?string
    {
        return $this->stringValue('reference');
    }

    public function rawData(): ?ChargePaymentRawDataResponse
    {
        $payload = $this->nestedPayload('raw_data');

        return $payload !== null ? ChargePaymentRawDataResponse::fromArray($payload) : null;
    }
}
