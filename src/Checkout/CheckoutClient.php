<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Checkout;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Support\Response;
use InvalidArgumentException;

final readonly class CheckoutClient
{
    public function __construct(private PagBankClient $client) {}

    public function createSession(): Response
    {
        return $this->client->requestSdk('POST', '/checkout-sdk/sessions');
    }

    public function calculateFees(int $value, string $bin, int $maxInstallments = 12, int $interestFreeInstallments = 0): Response
    {
        if ($value < 1 || ! preg_match('/^\d{6}$/D', $bin) || $maxInstallments < 1 || $maxInstallments > 12
            || $interestFreeInstallments < 0 || $interestFreeInstallments === 1 || $interestFreeInstallments > $maxInstallments) {
            throw new InvalidArgumentException('Invalid installment simulation parameters.');
        }

        return $this->client->requestApi('GET', '/charges/fees/calculate', ['query' => [
            'payment_methods' => 'CREDIT_CARD', 'value' => $value, 'credit_card_bin' => $bin,
            'max_installments' => $maxInstallments, 'max_installments_no_interest' => $interestFreeInstallments,
            'show_seller_fees' => 'true',
        ]]);
    }
}
