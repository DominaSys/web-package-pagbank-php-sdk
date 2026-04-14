<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderShippingResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function address(): ?OrderAddressResponse
    {
        $payload = $this->nestedPayload('address');

        return $payload !== null ? OrderAddressResponse::fromArray($payload) : null;
    }
}
