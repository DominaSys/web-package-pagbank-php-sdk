<?php

declare(strict_types=1);

use Dominasys\PagBank\Charges\Dto\ChargeAmountData;
use Dominasys\PagBank\Charges\Dto\ChargeCaptureData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitFeeLiableData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitReceiverAccountData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitReceiverAmountData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitReceiverConfigurationsData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitReceiverData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitRefundData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitRoundingLiableData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelSplitsData;
use Dominasys\PagBank\Charges\Enums\ChargeCancelSplitMethod;
use Dominasys\PagBank\Charges\Response\ChargeAmountResponse;
use Dominasys\PagBank\Charges\Response\ChargePaymentMethodResponse;
use Dominasys\PagBank\Charges\Response\ChargePaymentResponse;
use Dominasys\PagBank\Charges\Response\ChargeResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

final class ChargesValueObjectsTest extends TestCase
{
    public function testCaptureAndCancelPayloadsConvertToApiShape(): void
    {
        $capture = new ChargeCaptureData(
            amount: new ChargeAmountData(150099),
        );

        self::assertSame([
            'amount' => [
                'value' => 150099,
                'currency' => 'BRL',
            ],
        ], $capture->toArray());

        $cancel = new ChargeCancelData(
            amount: new ChargeAmountData(150099),
            splits: new ChargeCancelSplitsData(
                method: ChargeCancelSplitMethod::Fixed,
                receivers: [
                    new ChargeCancelSplitReceiverData(
                        account: new ChargeCancelSplitReceiverAccountData('ACCO_123'),
                        amount: new ChargeCancelSplitReceiverAmountData(1000),
                        configurations: new ChargeCancelSplitReceiverConfigurationsData(
                            refund: new ChargeCancelSplitRefundData(
                                roundingLiable: new ChargeCancelSplitRoundingLiableData(true),
                            ),
                            feeLiable: new ChargeCancelSplitFeeLiableData(100),
                        ),
                    ),
                ],
            ),
        );

        self::assertSame([
            'amount' => [
                'value' => 150099,
                'currency' => 'BRL',
            ],
            'splits' => [
                'method' => 'FIXED',
                'receivers' => [
                    [
                        'account' => [
                            'id' => 'ACCO_123',
                        ],
                        'amount' => [
                            'value' => 1000,
                        ],
                        'configurations' => [
                            'refund' => [
                                'rounding_liable' => [
                                    'apply' => true,
                                ],
                            ],
                            'fee_liable' => [
                                'percentage' => 100,
                            ],
                        ],
                    ],
                ],
            ],
        ], $cancel->toArray());
    }

    public function testChargeResponseExposesTypedAccessors(): void
    {
        $response = ChargeResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'id' => 'CHAR_123',
                'status' => 'PAID',
                'created_at' => '2024-01-01T10:00:00-03:00',
                'paid_at' => '2024-01-01T10:01:00-03:00',
                'reference_id' => 'charge-ref',
                'description' => 'Cobrança do pedido',
                'amount' => [
                    'value' => 150099,
                    'currency' => 'BRL',
                    'summary' => [
                        'total' => 150099,
                        'paid' => 150099,
                        'refunded' => 0,
                    ],
                ],
                'payment_response' => [
                    'code' => 20000,
                    'message' => 'SUCESSO',
                    'reference' => '032416400102',
                    'raw_data' => [
                        'authorization_code' => 308654,
                        'nsu' => '032416400102',
                        'reason_code' => '70',
                        'merchant_advice_code' => '80',
                    ],
                ],
                'payment_method' => [
                    'type' => 'CREDIT_CARD',
                    'installments' => 6,
                    'capture' => true,
                    'capture_before' => '2024-01-03T10:00:00-03:00',
                    'soft_descriptor' => 'DOMINASYS',
                    'card' => [
                        'id' => 'CARD_123',
                        'number' => '4111111111111111',
                        'network_token' => '1234567890000000',
                        'exp_month' => 12,
                        'exp_year' => 2026,
                        'security_code' => '123',
                        'store' => false,
                        'brand' => 'visa',
                        'product' => 'CREDIT',
                        'first_digits' => 411111,
                        'last_digits' => 1111,
                        'holder' => [
                            'name' => 'Jose da Silva',
                            'tax_id' => '12345678909',
                        ],
                    ],
                    'token_data' => [
                        'requestor_id' => '12345678901',
                        'wallet' => 'GOOGLE_PAY',
                        'cryptogram' => 'abcdef',
                        'ecommerce_domain' => 'example.com',
                        'assurance_level' => 2,
                    ],
                    'authentication_method' => [
                        'type' => 'THREEDS',
                        'id' => 'auth-1',
                        'cavv' => 'cavv',
                        'eci' => '05',
                        'xid' => 'xid',
                        'version' => '2.2.0',
                        'dstrans_id' => 'dstrans',
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
        )));

        self::assertSame('CHAR_123', $response->id());
        self::assertSame('PAID', $response->status());
        self::assertSame('2024-01-01T10:00:00-03:00', $response->createdAt());
        self::assertSame(150099, $response->amount()?->value());
        self::assertSame(150099, $response->amount()?->summary()?->total());
        self::assertSame(20000, $response->paymentResponse()?->code());
        self::assertSame('SUCESSO', $response->paymentResponse()?->message());
        self::assertSame('CREDIT_CARD', $response->paymentMethod()?->type());
        self::assertSame('visa', $response->paymentMethod()?->card()?->brand());
        self::assertSame('GOOGLE_PAY', $response->paymentMethod()?->tokenData()?->wallet());
        self::assertSame('THREEDS', $response->paymentMethod()?->authenticationMethod()?->type());
    }

}
