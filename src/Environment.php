<?php

declare(strict_types=1);

namespace Dominasys\PagBank;

enum Environment: string
{
    case Sandbox = 'sandbox';
    case Production = 'production';

    public function apiBaseUri(): string
    {
        return match ($this) {
            self::Sandbox => 'https://sandbox.api.pagseguro.com',
            self::Production => 'https://api.pagseguro.com',
        };
    }

    public function connectBaseUri(): string
    {
        return match ($this) {
            self::Sandbox => 'https://connect.sandbox.pagseguro.uol.com.br',
            self::Production => 'https://connect.pagseguro.uol.com.br',
        };
    }
}
