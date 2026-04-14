<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Enums;

enum OrderPaymentMethodType: string
{
    case CreditCard = 'CREDIT_CARD';
    case DebitCard = 'DEBIT_CARD';
    case Boleto = 'BOLETO';
    case Pix = 'PIX';
}
