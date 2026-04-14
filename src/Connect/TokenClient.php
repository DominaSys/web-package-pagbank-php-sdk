<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Enums\TokenTypeHint;
use Dominasys\PagBank\Connect\Response\TokenResponse;

final readonly class TokenClient
{
    public function __construct(
        private PagBankClient $client,
    ) {
    }

    public function exchangeAuthorizationCode(string $code, string $redirectUri): TokenResponse
    {
        return TokenResponse::fromResponse($this->client->requestApiWithConnectCredentials('POST', '/oauth2/token', [
            'json' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ],
        ]));
    }

    public function refreshAccessToken(string $refreshToken): TokenResponse
    {
        return TokenResponse::fromResponse($this->client->requestApiWithConnectCredentials('POST', '/oauth2/refresh', [
            'json' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ],
        ]));
    }

    public function revokeAccessToken(string $token, TokenTypeHint $tokenTypeHint = TokenTypeHint::AccessToken): void
    {
        $this->client->requestApiWithConnectCredentials('POST', '/oauth2/revoke', [
            'json' => [
                'token_type_hint' => $tokenTypeHint->value,
                'token' => $token,
            ],
        ]);
    }
}
