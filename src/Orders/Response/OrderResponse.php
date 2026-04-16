<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

use Dominasys\PagBank\Support\Response;

final class OrderResponse extends OrderResponseNode
{
    public function __construct(
        private readonly int $statusCode,
        array $payload,
    ) {
        parent::__construct($payload);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response->statusCode(), $response->toArray());
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function referenceId(): ?string
    {
        return $this->stringValue('reference_id');
    }

    public function createdAt(): ?string
    {
        return $this->stringValue('created_at');
    }

    public function customer(): ?OrderCustomerResponse
    {
        $payload = $this->nestedPayload('customer');

        return $payload !== null ? OrderCustomerResponse::fromArray($payload) : null;
    }

    /**
     * @return array<int, OrderItemResponse>
     */
    public function items(): array
    {
        return array_values(array_map(
            OrderItemResponse::fromArray(...),
            array_filter(
                $this->listPayload('items'),
                is_array(...),
            ),
        ));
    }

    public function shipping(): ?OrderShippingResponse
    {
        $payload = $this->nestedPayload('shipping');

        return $payload !== null ? OrderShippingResponse::fromArray($payload) : null;
    }

    /**
     * @return array<int, string>
     */
    public function notificationUrls(): array
    {
        $value = $this->listPayload('notification_urls');

        return array_values(array_filter($value, static fn (mixed $item): bool => is_string($item) && $item !== ''));
    }

    /**
     * @return array<int, OrderQrCodeResponse>
     */
    public function qrCodes(): array
    {
        return array_values(array_map(
            OrderQrCodeResponse::fromArray(...),
            array_filter(
                $this->listPayload('qr_codes'),
                is_array(...),
            ),
        ));
    }

    /**
     * @return array<int, OrderChargeResponse>
     */
    public function charges(): array
    {
        return array_values(array_map(
            OrderChargeResponse::fromArray(...),
            array_filter(
                $this->listPayload('charges'),
                is_array(...),
            ),
        ));
    }

    /**
     * @return array<int, OrderLinkResponse>
     */
    public function links(): array
    {
        return array_values(array_map(
            OrderLinkResponse::fromArray(...),
            array_filter(
                $this->listPayload('links'),
                is_array(...),
            ),
        ));
    }
}
