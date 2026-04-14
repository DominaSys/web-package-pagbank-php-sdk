<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderAmountResponse extends OrderResponseNode
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

    public function summary(): ?OrderAmountSummaryResponse
    {
        $payload = $this->nestedPayload('summary');

        return $payload !== null ? OrderAmountSummaryResponse::fromArray($payload) : null;
    }
}
