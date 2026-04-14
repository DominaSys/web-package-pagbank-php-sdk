<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderQrCodeData
{
    public function __construct(
        public OrderQrCodeAmountData $amount,
        public ?string $expirationDate = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'amount' => $this->amount->toArray(),
        ];

        if ($this->expirationDate !== null && $this->expirationDate !== '') {
            $payload['expiration_date'] = $this->expirationDate;
        }

        return $payload;
    }
}
