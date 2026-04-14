<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect;

use Dominasys\PagBank\Connect\Dto\AuthorizationUrlData;
use Dominasys\PagBank\Support\Configuration;

final readonly class AuthorizationUrlBuilder
{
    public function __construct(
        private Configuration $configuration,
    ) {
    }

    public function build(AuthorizationUrlData $data): string
    {
        $query = $data->toQuery();

        return rtrim($this->configuration->endpoints->connectBaseUri(), '/') . '/oauth2/authorize?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }
}
