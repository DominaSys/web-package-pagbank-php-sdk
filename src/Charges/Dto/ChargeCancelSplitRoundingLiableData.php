<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitRoundingLiableData
{
    public function __construct(
        public bool $apply,
    ) {
    }

    /**
     * @return array<string, bool>
     */
    public function toArray(): array
    {
        return [
            'apply' => $this->apply,
        ];
    }
}
