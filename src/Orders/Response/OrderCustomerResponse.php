<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderCustomerResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function name(): ?string
    {
        return $this->stringValue('name');
    }

    public function email(): ?string
    {
        return $this->stringValue('email');
    }

    public function taxId(): ?string
    {
        return $this->stringValue('tax_id');
    }

    /**
     * @return array<int, OrderPhoneResponse>
     */
    public function phones(): array
    {
        return array_values(array_map(
            static fn (array $payload): OrderPhoneResponse => OrderPhoneResponse::fromArray($payload),
            array_filter(
                $this->listPayload('phones'),
                static fn (mixed $payload): bool => is_array($payload),
            ),
        ));
    }
}
