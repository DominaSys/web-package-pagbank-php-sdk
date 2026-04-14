<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderPaymentMethodResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function type(): ?string
    {
        return $this->stringValue('type');
    }

    public function installments(): ?int
    {
        return $this->intValue('installments');
    }

    public function capture(): ?bool
    {
        return $this->boolValue('capture');
    }

    public function captureBefore(): ?string
    {
        return $this->stringValue('capture_before');
    }

    public function softDescriptor(): ?string
    {
        return $this->stringValue('soft_descriptor');
    }

    public function card(): ?OrderCardResponse
    {
        $payload = $this->nestedPayload('card');

        return $payload !== null ? OrderCardResponse::fromArray($payload) : null;
    }

    public function boleto(): ?OrderBoletoResponse
    {
        $payload = $this->nestedPayload('boleto');

        return $payload !== null ? OrderBoletoResponse::fromArray($payload) : null;
    }
}
