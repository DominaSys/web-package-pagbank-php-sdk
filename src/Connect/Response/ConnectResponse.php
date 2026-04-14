<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect\Response;

use Dominasys\PagBank\Support\Response;

abstract class ConnectResponse
{
    public function __construct(
        protected Response $response,
    ) {
    }

    public static function fromResponse(Response $response): static
    {
        return new static($response);
    }

    public function statusCode(): int
    {
        return $this->response->statusCode();
    }

    /**
     * @return array<string, mixed>
     */
    final protected function payload(): array
    {
        return $this->response->toArray();
    }
}
