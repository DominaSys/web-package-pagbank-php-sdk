<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Exceptions;

use Dominasys\PagBank\Support\Response;

class ApiException extends PagBankException
{
    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, array<int, string>>  $headers
     */
    public function __construct(
        string $message,
        public readonly int $statusCode,
        public readonly array $payload = [],
        public readonly array $headers = [],
    ) {
        parent::__construct($message, $statusCode);
    }

    public static function fromResponse(Response $response): self
    {
        $message = self::messageFromPayload($response->toArray(), $response->statusCode());

        return match (true) {
            in_array($response->statusCode(), [401, 403], true) => new AuthenticationException(
                $message,
                $response->statusCode(),
                $response->toArray(),
                $response->headers(),
            ),
            in_array($response->statusCode(), [400, 422], true) => new RequestValidationException(
                $message,
                $response->statusCode(),
                $response->toArray(),
                $response->headers(),
            ),
            default => new self(
                $message,
                $response->statusCode(),
                $response->toArray(),
                $response->headers(),
            ),
        };
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function messageFromPayload(array $payload, int $statusCode): string
    {
        foreach (['message', 'error_description', 'error', 'title', 'detail'] as $key) {
            if (isset($payload[$key]) && is_string($payload[$key]) && $payload[$key] !== '') {
                return $payload[$key];
            }
        }

        return sprintf('The PagBank API request failed with HTTP %d.', $statusCode);
    }
}
