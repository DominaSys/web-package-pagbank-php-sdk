<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

final class WebhookSignature
{
    public static function verify(string $rawBody, string $signature, #[\SensitiveParameter] string $token): bool
    {
        return $token !== '' && preg_match('/^[a-f0-9]{64}$/Di', $signature) === 1
            && hash_equals(hash('sha256', $token . '-' . $rawBody), strtolower($signature));
    }
}
