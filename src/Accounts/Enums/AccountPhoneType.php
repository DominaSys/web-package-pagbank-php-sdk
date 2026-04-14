<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Enums;

enum AccountPhoneType: string
{
    case Mobile = 'MOBILE';
    case Landline = 'LANDLINE';
}
