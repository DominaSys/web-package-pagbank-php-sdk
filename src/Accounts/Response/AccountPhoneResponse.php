<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Response;

use Dominasys\PagBank\Accounts\Enums\AccountPhoneType;

final class AccountPhoneResponse extends AccountResponseNode
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

    public function type(): ?AccountPhoneType
    {
        $value = $this->stringValue('type');

        return $value !== null ? AccountPhoneType::tryFrom($value) : null;
    }
}
