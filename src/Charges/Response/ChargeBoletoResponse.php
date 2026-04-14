<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

final class ChargeBoletoResponse extends ChargeResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
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
