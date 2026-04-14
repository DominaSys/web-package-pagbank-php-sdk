<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Response;

final class AccountTosAcceptanceResponse extends AccountResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function userIp(): ?string
    {
        return $this->stringValue('user_ip');
    }

    public function date(): ?string
    {
        return $this->stringValue('date');
    }
}
