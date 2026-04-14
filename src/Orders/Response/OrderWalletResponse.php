<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderWalletResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function type(): ?string
    {
        return $this->stringValue('type');
    }

    public function key(): ?string
    {
        return $this->stringValue('key');
    }
}
