<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderSplitReceiverData
{
    public function __construct(
        public OrderSplitReceiverAccountData $account,
        public int $value,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'account' => $this->account->toArray(),
            'value' => $this->value,
        ];
    }
}
