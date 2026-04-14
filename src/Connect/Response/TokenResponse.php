<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect\Response;

use Dominasys\PagBank\Support\Response;

final class TokenResponse extends ConnectResponse
{
    public function accessToken(): ?string
    {
        $value = $this->payload()['access_token'] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    public function refreshToken(): ?string
    {
        $value = $this->payload()['refresh_token'] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    public function tokenType(): ?string
    {
        $value = $this->payload()['token_type'] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    public function expiresIn(): ?int
    {
        $value = $this->payload()['expires_in'] ?? null;

        return is_int($value) ? $value : (is_numeric($value) ? (int) $value : null);
    }
}
