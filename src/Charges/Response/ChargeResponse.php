<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

use Dominasys\PagBank\Support\Response;

final class ChargeResponse extends ChargeResponseNode
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

    public function amount(): ?ChargeAmountResponse
    {
        $payload = $this->nestedPayload('amount');

        return $payload !== null ? ChargeAmountResponse::fromArray($payload) : null;
    }

    public function paymentResponse(): ?ChargePaymentResponse
    {
        $payload = $this->nestedPayload('payment_response');

        return $payload !== null ? ChargePaymentResponse::fromArray($payload) : null;
    }

    public function paymentMethod(): ?ChargePaymentMethodResponse
    {
        $payload = $this->nestedPayload('payment_method');

        return $payload !== null ? ChargePaymentMethodResponse::fromArray($payload) : null;
    }
}
