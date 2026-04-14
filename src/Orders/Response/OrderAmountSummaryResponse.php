<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderAmountSummaryResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function total(): ?int
    {
        return $this->intValue('total');
    }

    public function paid(): ?int
    {
        return $this->intValue('paid');
    }

    public function refunded(): ?int
    {
        return $this->intValue('refunded');
    }
}
