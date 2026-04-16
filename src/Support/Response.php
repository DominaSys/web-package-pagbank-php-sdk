<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

use JsonException;
use Psr\Http\Message\ResponseInterface;

final readonly class Response
{
    /**
     * @param  array<string, array<int, string>>  $headers
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        private int $statusCode,
        private array $headers,
        private array $payload,
        private string $body,
    ) {
    }

    public static function fromPsrResponse(ResponseInterface $response): self
    {
        $body = (string) $response->getBody();
        $decoded = [];

        if ($body !== '') {
            try {
                $decodedBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

                if (is_array($decodedBody)) {
                    $decoded = $decodedBody;
                }
            } catch (JsonException) {
            }
        }

        return new self(
            statusCode: $response->getStatusCode(),
            headers: $response->getHeaders(),
            payload: $decoded,
            body: $body,
        );
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return array<string, mixed>
     */
    public function json(): array
    {
        return $this->payload;
    }
}
