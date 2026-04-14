<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Response;

final class OrderBoletoInstructionLinesResponse extends OrderResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function line1(): ?string
    {
        return $this->stringValue('line_1');
    }

    public function line2(): ?string
    {
        return $this->stringValue('line_2');
    }
}
