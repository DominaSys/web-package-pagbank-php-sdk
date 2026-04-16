<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderBoletoResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function barcode(): ?string
    {
        return $this->stringValue('barcode');
    }

    public function formattedBarcode(): ?string
    {
        return $this->stringValue('formatted_barcode');
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
