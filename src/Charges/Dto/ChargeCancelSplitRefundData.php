<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitRefundData
{
    public function __construct(
        public ?ChargeCancelSplitRoundingLiableData $roundingLiable = null,
    ) {
    }

    /**
     * @return array<string, array<string, bool>>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->roundingLiable instanceof ChargeCancelSplitRoundingLiableData) {
            $payload['rounding_liable'] = $this->roundingLiable->toArray();
        }

        return $payload;
    }
}
