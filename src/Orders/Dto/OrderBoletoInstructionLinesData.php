<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderBoletoInstructionLinesData
{
    public function __construct(
        public ?string $line1 = null,
        public ?string $line2 = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->line1 !== null && $this->line1 !== '') {
            $payload['line_1'] = $this->line1;
        }

        if ($this->line2 !== null && $this->line2 !== '') {
            $payload['line_2'] = $this->line2;
        }

        return $payload;
    }
}
