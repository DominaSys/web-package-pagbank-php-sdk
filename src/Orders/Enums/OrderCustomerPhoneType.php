<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Enums;

enum OrderCustomerPhoneType: string
{
    case Mobile = 'MOBILE';
    case Business = 'BUSINESS';
    case Home = 'HOME';
}
