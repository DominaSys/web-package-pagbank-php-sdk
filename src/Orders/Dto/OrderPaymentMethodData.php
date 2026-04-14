<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

use Dominasys\PagBank\Orders\Enums\OrderPaymentMethodType;

final readonly class OrderPaymentMethodData
{
    public function __construct(
        public OrderPaymentMethodType $type,
        public ?int $installments = null,
        public ?bool $capture = null,
        public ?string $captureBefore = null,
        public ?string $softDescriptor = null,
        public ?OrderCardData $card = null,
        public ?OrderBoletoData $boleto = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = ['type' => $this->type->value];

        foreach ([
            'installments' => $this->installments,
            'capture' => $this->capture,
            'capture_before' => $this->captureBefore,
            'soft_descriptor' => $this->softDescriptor,
        ] as $key => $value) {
            if ($value !== null && $value !== '') {
                $payload[$key] = $value;
            }
        }

        if ($this->card instanceof OrderCardData) {
            $payload['card'] = $this->card->toArray();
        }

        if ($this->boleto instanceof OrderBoletoData) {
            $payload['boleto'] = $this->boleto->toArray();
        }

        return $payload;
    }
}
