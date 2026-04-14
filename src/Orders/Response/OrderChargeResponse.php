<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderChargeResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function status(): ?string
    {
        return $this->stringValue('status');
    }

    public function createdAt(): ?string
    {
        return $this->stringValue('created_at');
    }

    public function paidAt(): ?string
    {
        return $this->stringValue('paid_at');
    }

    public function referenceId(): ?string
    {
        return $this->stringValue('reference_id');
    }

    public function description(): ?string
    {
        return $this->stringValue('description');
    }

    public function amount(): ?OrderAmountResponse
    {
        $payload = $this->nestedPayload('amount');

        return $payload !== null ? OrderAmountResponse::fromArray($payload) : null;
    }

    public function paymentResponse(): ?OrderPaymentResponse
    {
        $payload = $this->nestedPayload('payment_response');

        return $payload !== null ? OrderPaymentResponse::fromArray($payload) : null;
    }

    public function paymentMethod(): ?OrderPaymentMethodResponse
    {
        $payload = $this->nestedPayload('payment_method');

        return $payload !== null ? OrderPaymentMethodResponse::fromArray($payload) : null;
    }
}
