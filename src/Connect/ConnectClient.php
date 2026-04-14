<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect;

use Dominasys\PagBank\Connect\Dto\AuthorizationUrlData;
use Dominasys\PagBank\Connect\Dto\CreateApplicationData;
use Dominasys\PagBank\Connect\Response\ApplicationResponse;
use Dominasys\PagBank\Connect\Response\TokenResponse;
use Dominasys\PagBank\Enums\TokenTypeHint;

final readonly class ConnectClient
{
    public function __construct(
        private AuthorizationUrlBuilder $authorizationUrlBuilder,
        private ApplicationClient $applicationClient,
        private TokenClient $tokenClient,
    ) {
    }

    public function createApplication(CreateApplicationData $payload): ApplicationResponse
    {
        return $this->applicationClient->create($payload);
    }

    public function getApplication(string $clientId): ApplicationResponse
    {
        return $this->applicationClient->get($clientId);
    }

    public function authorizationUrl(AuthorizationUrlData $data): string
    {
        return $this->authorizationUrlBuilder->build($data);
    }

    public function exchangeAuthorizationCode(string $code, string $redirectUri): TokenResponse
    {
        return $this->tokenClient->exchangeAuthorizationCode($code, $redirectUri);
    }

    public function refreshAccessToken(string $refreshToken): TokenResponse
    {
        return $this->tokenClient->refreshAccessToken($refreshToken);
    }

    public function revokeAccessToken(string $token, TokenTypeHint $tokenTypeHint = TokenTypeHint::AccessToken): void
    {
        $this->tokenClient->revokeAccessToken($token, $tokenTypeHint);
    }
}
