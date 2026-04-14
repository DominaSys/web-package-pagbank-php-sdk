<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderBoletoResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function dueDate(): ?string
    {
        return $this->stringValue('due_date');
    }

    public function instructionLines(): ?OrderBoletoInstructionLinesResponse
    {
        $payload = $this->nestedPayload('instruction_lines');

        return $payload !== null ? OrderBoletoInstructionLinesResponse::fromArray($payload) : null;
    }

    public function holder(): ?OrderBoletoHolderResponse
    {
        $payload = $this->nestedPayload('holder');

        return $payload !== null ? OrderBoletoHolderResponse::fromArray($payload) : null;
    }
}
