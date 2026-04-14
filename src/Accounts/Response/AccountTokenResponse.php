<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Response;

final class AccountTokenResponse extends AccountResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function tokenType(): ?string
    {
        return $this->stringValue('token_type');
    }

    public function accessToken(): ?string
    {
        return $this->stringValue('access_token');
    }

    public function expiresIn(): ?int
    {
        return $this->intValue('expires_in');
    }

    public function refreshToken(): ?string
    {
        return $this->stringValue('refresh_token');
    }

    public function scope(): ?string
    {
        return $this->stringValue('scope');
    }
}
