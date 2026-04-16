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
            OrderLinkResponse::fromArray(...),
            array_filter(
                $this->listPayload('links'),
                is_array(...),
            ),
        ));
    }
}
