<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

use Dominasys\PagBank\Orders\Enums\OrderCustomerPhoneType;

final class OrderPhoneResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function country(): ?string
    {
        return $this->stringValue('country');
    }

    public function area(): ?string
    {
        return $this->stringValue('area');
    }

    public function number(): ?string
    {
        return $this->stringValue('number');
    }

    public function type(): ?OrderCustomerPhoneType
    {
        $value = $this->stringValue('type');

        return $value !== null ? OrderCustomerPhoneType::tryFrom($value) : null;
    }
}
