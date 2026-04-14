<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargeAuthenticationMethodResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function type(): ?string
    {
        return $this->stringValue('type');
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function cavv(): ?string
    {
        return $this->stringValue('cavv');
    }

    public function eci(): ?string
    {
        return $this->stringValue('eci');
    }

    public function xid(): ?string
    {
        return $this->stringValue('xid');
    }

    public function version(): ?string
    {
        return $this->stringValue('version');
    }

    public function dstransId(): ?string
    {
        return $this->stringValue('dstrans_id');
    }
}
