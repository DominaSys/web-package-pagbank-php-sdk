<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards;

use Dominasys\PagBank\Cards\Dto\CardEncryptData;
use Dominasys\PagBank\Cards\Dto\CardStoreData;
use Dominasys\PagBank\Cards\Response\CardEncryptionResult;
use Dominasys\PagBank\Cards\Response\CardResponse;
use Dominasys\PagBank\Client\PagBankClient;

final readonly class CardsClient
{
    public function __construct(
        private PagBankClient $client,
        private CardEncryptor $encryptor,
    ) {
    }

    public function encryptCard(CardEncryptData $data): CardEncryptionResult
    {
        return $this->encryptor->encrypt($data);
    }

    public function validateAndStoreCard(CardStoreData $data): CardResponse
    {
        return CardResponse::fromResponse($this->client->requestApi('POST', '/tokens/cards', [
            'json' => $data->toArray(),
        ]));
    }
}
