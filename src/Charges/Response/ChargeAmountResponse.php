<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargeAmountResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function value(): ?int
    {
        return $this->intValue('value');
    }

    public function currency(): ?string
    {
        return $this->stringValue('currency');
    }

    public function summary(): ?ChargeAmountSummaryResponse
    {
        $payload = $this->nestedPayload('summary');

        return $payload !== null ? ChargeAmountSummaryResponse::fromArray($payload) : null;
    }
}
