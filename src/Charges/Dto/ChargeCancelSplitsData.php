<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

use Dominasys\PagBank\Charges\Enums\ChargeCancelSplitMethod;

final readonly class ChargeCancelSplitsData
{
    /**
     * @param  array<int, ChargeCancelSplitReceiverData>  $receivers
     */
    public function __construct(
        public ChargeCancelSplitMethod $method,
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
                static fn (ChargeCancelSplitReceiverData $receiver): array => $receiver->toArray(),
                $this->receivers,
            ),
        ];
    }
}
