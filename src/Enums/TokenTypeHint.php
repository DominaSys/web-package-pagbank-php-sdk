<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Enums;

enum TokenTypeHint: string
{
    case AccessToken = 'access_token';
    case RefreshToken = 'refresh_token';
}
