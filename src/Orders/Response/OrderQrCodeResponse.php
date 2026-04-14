<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderQrCodeResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function expirationDate(): ?string
    {
        return $this->stringValue('expiration_date');
    }

    public function text(): ?string
    {
        return $this->stringValue('text');
    }

    public function amount(): ?OrderQrCodeAmountResponse
    {
        $payload = $this->nestedPayload('amount');

        return $payload !== null ? OrderQrCodeAmountResponse::fromArray($payload) : null;
    }

    /**
     * @return array<int, OrderLinkResponse>
     */
    public function links(): array
    {
        return array_values(array_map(
            static fn (array $payload): OrderLinkResponse => OrderLinkResponse::fromArray($payload),
            array_filter(
                $this->listPayload('links'),
                static fn (mixed $payload): bool => is_array($payload),
            ),
        ));
    }
}
