<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts;

use Dominasys\PagBank\Accounts\Dto\AccountData;
use Dominasys\PagBank\Accounts\Response\AccountResponse;
use Dominasys\PagBank\Client\PagBankClient;

final readonly class AccountsClient
{
    public function __construct(
        private PagBankClient $client,
    ) {
    }

    public function createAccount(AccountData $data): AccountResponse
    {
        return AccountResponse::fromResponse($this->client->requestApiWithAccountCredentials('POST', '/accounts', [
            'json' => $data->toArray(),
        ]));
    }

    public function getAccount(string $accountId, string $clientToken): AccountResponse
    {
        return AccountResponse::fromResponse($this->client->requestApiWithClientToken('GET', '/accounts/' . rawurlencode($accountId), $clientToken));
    }
}
