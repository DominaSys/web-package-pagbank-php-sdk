<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards\Response;

final readonly class CardEncryptionResult
{
    /**
     * @param  array<int, CardEncryptionError>  $errors
     */
    public function __construct(
        private ?string $encryptedCard,
        private array $errors = [],
    ) {
    }

    public function encryptedCard(): ?string
    {
        return $this->encryptedCard;
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * @return array<int, CardEncryptionError>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
