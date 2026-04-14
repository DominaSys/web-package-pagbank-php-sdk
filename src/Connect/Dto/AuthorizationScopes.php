<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect\Dto;

final readonly class AuthorizationScopes
{
    private array $values;

    public function __construct(string ...$values)
    {
        $this->values = $values;
    }

    public function toString(): string
    {
        return implode(' ', array_values(array_filter($this->values, static fn (string $value): bool => $value !== '')));
    }
}
