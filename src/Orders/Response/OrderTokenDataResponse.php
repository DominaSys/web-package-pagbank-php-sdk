<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderTokenDataResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function requestorId(): ?string
    {
        return $this->stringValue('requestor_id');
    }

    public function wallet(): ?string
    {
        return $this->stringValue('wallet');
    }

    public function cryptogram(): ?string
    {
        return $this->stringValue('cryptogram');
    }

    public function ecommerceDomain(): ?string
    {
        return $this->stringValue('ecommerce_domain');
    }

    public function assuranceLevel(): ?int
    {
        return $this->intValue('assurance_level');
    }
}
