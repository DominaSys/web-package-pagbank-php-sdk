<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderSplitReceiverAccountData
{
    public function __construct(
        public string $id,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}
