<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Enums;

enum OrderSplitMethod: string
{
    case Fixed = 'FIXED';
    case Percentage = 'PERCENTAGE';
}
