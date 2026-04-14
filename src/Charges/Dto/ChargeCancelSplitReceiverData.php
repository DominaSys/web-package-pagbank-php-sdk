<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitReceiverData
{
    public function __construct(
        public ChargeCancelSplitReceiverAccountData $account,
        public ChargeCancelSplitReceiverAmountData $amount,
        public ?ChargeCancelSplitReceiverConfigurationsData $configurations = null,
    ) {
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        $payload = [
            'account' => $this->account->toArray(),
            'amount' => $this->amount->toArray(),
        ];

        if ($this->configurations instanceof ChargeCancelSplitReceiverConfigurationsData) {
            $payload['configurations'] = $this->configurations->toArray();
        }

        return $payload;
    }
}
