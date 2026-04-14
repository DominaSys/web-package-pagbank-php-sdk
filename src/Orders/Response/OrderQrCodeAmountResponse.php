<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderQrCodeAmountResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function value(): ?int
    {
        return $this->intValue('value');
    }
}
