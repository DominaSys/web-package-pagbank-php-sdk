<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

use Dominasys\PagBank\Orders\Enums\OrderSplitMethod;

final readonly class OrderSplitData
{
    /**
     * @param  array<int, OrderSplitReceiverData>  $receivers
     */
    public function __construct(
        public OrderSplitMethod $method,
        public array $receivers,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'method' => $this->method->value,
            'receivers' => array_map(
                static fn (OrderSplitReceiverData $receiver): array => $receiver->toArray(),
                $this->receivers,
            ),
        ];
    }
}
