<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Enums;

enum AccountType: string
{
    case Buyer = 'BUYER';
    case Seller = 'SELLER';
    case Enterprise = 'ENTERPRISE';
}
