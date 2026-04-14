<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class CreateOrderData
{
    /**
     * @param  array<int, OrderItemData>  $items
     * @param  array<int, string>  $notificationUrls
     * @param  array<int, OrderQrCodeData>  $qrCodes
     * @param  array<int, OrderChargeData>  $charges
     */
    public function __construct(
        public ?string $referenceId = null,
        public ?OrderCustomerData $customer = null,
        public array $items = [],
        public ?OrderShippingData $shipping = null,
        public array $notificationUrls = [],
        public array $qrCodes = [],
        public array $charges = [],
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

        if ($this->customer instanceof OrderCustomerData) {
            $payload['customer'] = $this->customer->toArray();
        }

        if ($this->items !== []) {
            $payload['items'] = array_map(
                static fn (OrderItemData $item): array => $item->toArray(),
                $this->items,
            );
        }

        if ($this->shipping instanceof OrderShippingData) {
            $payload['shipping'] = $this->shipping->toArray();
        }

        if ($this->notificationUrls !== []) {
            $payload['notification_urls'] = array_values($this->notificationUrls);
        }

        if ($this->qrCodes !== []) {
            $payload['qr_codes'] = array_map(
                static fn (OrderQrCodeData $qrCode): array => $qrCode->toArray(),
                $this->qrCodes,
            );
        }

        if ($this->charges !== []) {
            $payload['charges'] = array_map(
                static fn (OrderChargeData $charge): array => $charge->toArray(),
                $this->charges,
            );
        }

        return $payload;
    }
}
