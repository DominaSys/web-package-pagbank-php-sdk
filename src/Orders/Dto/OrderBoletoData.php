<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderBoletoData
{
    public function __construct(
        public ?string $dueDate = null,
        public ?OrderBoletoInstructionLinesData $instructionLines = null,
        public ?OrderBoletoHolderData $holder = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->dueDate !== null && $this->dueDate !== '') {
            $payload['due_date'] = $this->dueDate;
        }

        if ($this->instructionLines instanceof OrderBoletoInstructionLinesData) {
            $payload['instruction_lines'] = $this->instructionLines->toArray();
        }

        if ($this->holder instanceof OrderBoletoHolderData) {
            $payload['holder'] = $this->holder->toArray();
        }

        return $payload;
    }
}
