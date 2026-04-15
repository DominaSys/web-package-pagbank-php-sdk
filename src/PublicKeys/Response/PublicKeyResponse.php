<?php

declare(strict_types=1);

namespace Dominasys\PagBank\PublicKeys\Response;

use Dominasys\PagBank\Support\Response;
use Dominasys\PagBank\Support\ResponseNode;

final class PublicKeyResponse extends ResponseNode
{
    public static function fromResponse(Response $response): self
    {
        $payload = $response->toArray();

        if ($payload === []) {
            $body = trim($response->body());

            if ($body !== '') {
                $decoded = json_decode($body, true);

                if (is_string($decoded) && $decoded !== '') {
                    $payload = ['public_key' => $decoded];
                } elseif (is_array($decoded) && $decoded !== []) {
                    $payload = $decoded;
                } else {
                    $payload = ['public_key' => $body];
                }
            }
        }

        if (isset($payload['key']) && !isset($payload['public_key'])) {
            $payload['public_key'] = $payload['key'];
        }

        return self::fromArrayPayload($payload);
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function type(): ?string
    {
        return $this->stringValue('type');
    }

    public function publicKey(): ?string
    {
        return $this->stringValue('public_key') ?? $this->stringValue('key');
    }

    public function createdAt(): ?string
    {
        return $this->stringValue('created_at');
    }

    public function updatedAt(): ?string
    {
        return $this->stringValue('updated_at');
    }
}
