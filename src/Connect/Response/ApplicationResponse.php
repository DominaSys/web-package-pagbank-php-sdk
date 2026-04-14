<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect\Response;

final class ApplicationResponse extends ConnectResponse
{
    public function clientId(): ?string
    {
        $value = $this->payload()['client_id'] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    public function applicationId(): ?string
    {
        $value = $this->payload()['id'] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }
}
