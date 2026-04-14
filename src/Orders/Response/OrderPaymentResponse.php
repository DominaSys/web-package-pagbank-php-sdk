<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderPaymentResponse extends OrderResponseNode
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

    public function rawData(): ?OrderPaymentRawDataResponse
    {
        $payload = $this->nestedPayload('raw_data');

        return $payload !== null ? OrderPaymentRawDataResponse::fromArray($payload) : null;
    }
}
