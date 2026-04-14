<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitReceiverAmountData
{
    public function __construct(
        public int $value,
    ) {
        if ($this->value <= 0) {
            throw new \InvalidArgumentException('The split receiver amount must be positive.');
        }
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
