<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargeBoletoResponse extends ChargeResponseNode
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

    public function instructionLines(): ?ChargeBoletoInstructionLinesResponse
    {
        $payload = $this->nestedPayload('instruction_lines');

        return $payload !== null ? ChargeBoletoInstructionLinesResponse::fromArray($payload) : null;
    }

    public function holder(): ?ChargeBoletoHolderResponse
    {
        $payload = $this->nestedPayload('holder');

        return $payload !== null ? ChargeBoletoHolderResponse::fromArray($payload) : null;
    }
}
