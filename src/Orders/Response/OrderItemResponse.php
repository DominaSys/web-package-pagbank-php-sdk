<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderItemResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function referenceId(): ?string
    {
        return $this->stringValue('reference_id');
    }

    public function name(): ?string
    {
        return $this->stringValue('name');
    }

    public function quantity(): ?int
    {
        return $this->intValue('quantity');
    }

    public function unitAmount(): ?int
    {
        return $this->intValue('unit_amount');
    }
}
