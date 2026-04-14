<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderItemData
{
    public function __construct(
        public string $referenceId,
        public string $name,
        public int $quantity,
        public int $unitAmount,
    ) {
        if ($this->referenceId === '' || $this->name === '' || $this->quantity <= 0 || $this->unitAmount <= 0) {
            throw new \InvalidArgumentException('Order items must have a reference id, name, quantity and unit amount.');
        }
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'reference_id' => $this->referenceId,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit_amount' => $this->unitAmount,
        ];
    }
}
