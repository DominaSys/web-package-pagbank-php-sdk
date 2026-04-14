<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges\Enums;

enum ChargeCancelSplitMethod: string
{
    case Fixed = 'FIXED';
    case Percentage = 'PERCENTAGE';
}
