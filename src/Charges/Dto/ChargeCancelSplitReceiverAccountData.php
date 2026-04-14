<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Dto;

final readonly class ChargeCancelSplitReceiverAccountData
{
    public function __construct(
        public string $id,
    ) {
        if ($this->id === '') {
            throw new \InvalidArgumentException('The split receiver account id cannot be empty.');
        }
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
