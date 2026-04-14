<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderChargeData
{
    /**
     * @param  array<int, string>  $notificationUrls
     */
    public function __construct(
        public ?string $referenceId = null,
        public ?string $description = null,
        public ?OrderAmountData $amount = null,
        public ?OrderPaymentMethodData $paymentMethod = null,
        public array $notificationUrls = [],
        public ?OrderSplitData $splits = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->referenceId !== null && $this->referenceId !== '') {
            $payload['reference_id'] = $this->referenceId;
        }

        if ($this->description !== null && $this->description !== '') {
            $payload['description'] = $this->description;
        }

        if ($this->amount instanceof OrderAmountData) {
            $payload['amount'] = $this->amount->toArray();
        }

        if ($this->paymentMethod instanceof OrderPaymentMethodData) {
            $payload['payment_method'] = $this->paymentMethod->toArray();
        }

        if ($this->notificationUrls !== []) {
            $payload['notification_urls'] = array_values($this->notificationUrls);
        }

        if ($this->splits instanceof OrderSplitData) {
            $payload['splits'] = $this->splits->toArray();
        }

        return $payload;
    }
}
