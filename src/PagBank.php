<?php

declare(strict_types=1);

namespace Dominasys\PagBank;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Accounts\AccountsClient;
use Dominasys\PagBank\Connect\ConnectClient;
use Dominasys\PagBank\Connect\AuthorizationUrlBuilder;
use Dominasys\PagBank\Connect\ApplicationClient;
use Dominasys\PagBank\Connect\TokenClient;
use Dominasys\PagBank\Support\Configuration;
use GuzzleHttp\ClientInterface;

final class PagBank
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly ?ClientInterface $httpClient = null,
    ) {
    }

    private ?PagBankClient $client = null;

    public static function make(Configuration $configuration, ?ClientInterface $httpClient = null): self
    {
        return new self($configuration, $httpClient);
    }

    public function connect(): ConnectClient
    {
        $client = $this->client();

        return new ConnectClient(
            new AuthorizationUrlBuilder($this->configuration),
            new ApplicationClient($client),
            new TokenClient($client),
        );
    }

    public function accounts(): AccountsClient
    {
        return new AccountsClient($this->client());
    }

    private function client(): PagBankClient
    {
        if ($this->client instanceof PagBankClient) {
            return $this->client;
        }

        return $this->client = new PagBankClient($this->configuration, $this->httpClient);
    }
}
