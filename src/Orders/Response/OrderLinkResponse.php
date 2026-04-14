<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderLinkResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function rel(): ?string
    {
        return $this->stringValue('rel');
    }

    public function href(): ?string
    {
        return $this->stringValue('href');
    }

    public function media(): ?string
    {
        return $this->stringValue('media');
    }

    public function type(): ?string
    {
        return $this->stringValue('type');
    }
}
