<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Connect\Dto\CreateApplicationData;
use Dominasys\PagBank\Connect\Response\ApplicationResponse;

final readonly class ApplicationClient
{
    public function __construct(
        private PagBankClient $client,
    ) {
    }

    public function create(CreateApplicationData $payload): ApplicationResponse
    {
        return ApplicationResponse::fromResponse($this->client->requestApi('POST', '/oauth2/application', [
            'json' => $payload->toArray(),
        ]));
    }

    public function get(string $clientId): ApplicationResponse
    {
        return ApplicationResponse::fromResponse($this->client->requestApi('GET', '/oauth2/application/' . rawurlencode($clientId)));
    }
}
