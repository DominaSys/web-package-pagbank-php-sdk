<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards\Dto;

final readonly class CardStoreData
{
    private const string MODE_ENCRYPTED = 'encrypted';

    private const string MODE_PCI = 'pci';

    private function __construct(
        private string $mode,
        private ?string $encrypted = null,
        private ?string $number = null,
        private ?int $expMonth = null,
        private ?int $expYear = null,
        private ?string $securityCode = null,
        private ?CardHolderData $holder = null,
    ) {
    }

    public static function encrypted(string $encrypted, ?CardHolderData $holder = null): self
    {
        if ($encrypted === '') {
            throw new \InvalidArgumentException('The encrypted card payload cannot be empty.');
        }

        return new self(self::MODE_ENCRYPTED, encrypted: $encrypted, holder: $holder);
    }

    public static function pci(
        string $number,
        int $expMonth,
        int $expYear,
        string $securityCode,
        CardHolderData $holder,
    ): self {
        if ($number === '' || $expMonth <= 0 || $expYear <= 0 || $securityCode === '') {
            throw new \InvalidArgumentException('The PCI card payload is invalid.');
        }

        return new self(
            self::MODE_PCI,
            number: $number,
            expMonth: $expMonth,
            expYear: $expYear,
            securityCode: $securityCode,
            holder: $holder,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->mode === self::MODE_ENCRYPTED) {
            $payload['encrypted'] = $this->encrypted;
        } else {
            $payload['number'] = $this->number;
            $payload['exp_month'] = $this->expMonth;
            $payload['exp_year'] = $this->expYear;
            $payload['security_code'] = $this->securityCode;
        }

        if ($this->holder instanceof CardHolderData) {
            $payload['holder'] = $this->holder->toArray();
        }

        return $payload;
    }
}
