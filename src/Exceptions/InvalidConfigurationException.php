<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Exceptions;

final class InvalidConfigurationException extends PagBankException
{
    public static function missingBearerToken(): self
    {
        return new self('The PagBank bearer token is required for this operation.');
    }

    public static function missingConnectCredentials(): self
    {
        return new self('The PagBank client_id and client_secret are required for this operation.');
    }

    public static function missingAccountClientToken(): self
    {
        return new self('The PagBank x-client-token is required for this operation.');
    }

    public static function invalidTimeout(string $name): self
    {
        return new self(sprintf('The %s must be greater than zero.', $name));
    }
}
