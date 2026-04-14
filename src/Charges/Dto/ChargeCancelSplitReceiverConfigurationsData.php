<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitReceiverConfigurationsData
{
    public function __construct(
        public ?ChargeCancelSplitRefundData $refund = null,
        public ?ChargeCancelSplitFeeLiableData $feeLiable = null,
    ) {
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->refund instanceof ChargeCancelSplitRefundData) {
            $payload['refund'] = $this->refund->toArray();
        }

        if ($this->feeLiable instanceof ChargeCancelSplitFeeLiableData) {
            $payload['fee_liable'] = $this->feeLiable->toArray();
        }

        return $payload;
    }
}
