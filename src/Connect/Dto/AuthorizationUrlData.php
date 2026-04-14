<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect\Dto;

final readonly class AuthorizationUrlData
{
    public function __construct(
        public string $clientId,
        public string $redirectUri,
        public AuthorizationScopes $scopes,
        public ?string $state = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toQuery(): array
    {
        return array_filter([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'scope' => $this->scopes->toString(),
            'state' => $this->state,
        ], static fn (mixed $value): bool => $value !== null && $value !== '');
    }
}
