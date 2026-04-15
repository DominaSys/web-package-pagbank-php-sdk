<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Cards\Response;

use Dominasys\PagBank\Support\ResponseNode;

final class CardHolderResponse extends ResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function name(): ?string
    {
        return $this->stringValue('name');
    }

    public function taxId(): ?string
    {
        return $this->stringValue('tax_id');
    }
}
