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
        if (isset($payload['error_messages']) && is_array($payload['error_messages']) && $payload['error_messages'] !== []) {
            $message = self::messageFromErrorMessages($payload['error_messages']);

            if ($message !== '') {
                return $message;
            }
        }

        foreach (['message', 'error_description', 'error', 'title', 'detail'] as $key) {
            if (isset($payload[$key]) && is_string($payload[$key]) && $payload[$key] !== '') {
                return $payload[$key];
            }
        }

        return sprintf('The PagBank API request failed with HTTP %d.', $statusCode);
    }

    /**
     * @param  array<int, mixed>  $errorMessages
     */
    private static function messageFromErrorMessages(array $errorMessages): string
    {
        $messages = [];

        foreach ($errorMessages as $errorMessage) {
            if (! is_array($errorMessage)) {
                continue;
            }

            $parts = [];

            if (isset($errorMessage['code']) && is_string($errorMessage['code']) && $errorMessage['code'] !== '') {
                $parts[] = $errorMessage['code'];
            }

            if (isset($errorMessage['description']) && is_string($errorMessage['description']) && $errorMessage['description'] !== '') {
                $parts[] = $errorMessage['description'];
            }

            if (isset($errorMessage['parameter_name']) && is_string($errorMessage['parameter_name']) && $errorMessage['parameter_name'] !== '') {
                $parts[] = sprintf('(%s)', $errorMessage['parameter_name']);
            }

            if (isset($errorMessage['errors']) && is_array($errorMessage['errors']) && $errorMessage['errors'] !== []) {
                $details = array_values(array_filter(
                    $errorMessage['errors'],
                    static fn ($detail): bool => is_string($detail) && $detail !== '',
                ));

                if ($details !== []) {
                    $parts[] = sprintf(': %s', implode('; ', $details));
                }
            }

            if ($parts !== []) {
                $messages[] = implode(' ', $parts);
            }
        }

        return implode('; ', $messages);
    }
}
