<?php

declare(strict_types=1);

namespace Dominasys\PagBank\PublicKeys;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\PublicKeys\Response\PublicKeyResponse;

final readonly class PublicKeysClient
{
    private const string RESOURCE_TYPE_CARD = 'card';

    public function __construct(
        private PagBankClient $client,
    ) {
    }

    public function createCardPublicKey(): PublicKeyResponse
    {
        return PublicKeyResponse::fromResponse($this->client->requestApi('POST', '/public-keys', [
            'json' => [
                'type' => self::RESOURCE_TYPE_CARD,
            ],
        ]));
    }

    public function getCardPublicKey(): PublicKeyResponse
    {
        return PublicKeyResponse::fromResponse($this->client->requestApi('GET', '/public-keys/card'));
    }

    public function updateCardPublicKey(): PublicKeyResponse
    {
        return PublicKeyResponse::fromResponse($this->client->requestApi('PUT', '/public-keys/card'));
    }
}
