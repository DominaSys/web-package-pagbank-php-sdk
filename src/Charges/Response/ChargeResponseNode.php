<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Response;

use Dominasys\PagBank\Support\ResponseNode as SupportResponseNode;

abstract class ChargeResponseNode extends SupportResponseNode
{
    /**
     * @return array<int, mixed>
     */
    protected function listPayload(string $key): array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) ? $value : [];
    }
}
