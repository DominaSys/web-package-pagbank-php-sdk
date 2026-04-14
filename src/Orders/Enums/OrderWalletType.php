<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Enums;

enum OrderWalletType: string
{
    case ApplePay = 'APPLE_PAY';
    case GooglePay = 'GOOGLE_PAY';
    case SamsungPay = 'SAMSUNG_PAY';
    case MerchantTokenizationProgram = 'MERCHANT_TOKENIZATION_PROGRAM';
}
