<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitFeeLiableData
{
    public function __construct(
        public int $percentage,
    ) {
        if ($this->percentage <= 0) {
            throw new \InvalidArgumentException('The split receiver fee liable percentage must be positive.');
        }
    }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'percentage' => $this->percentage,
        ];
    }
}
