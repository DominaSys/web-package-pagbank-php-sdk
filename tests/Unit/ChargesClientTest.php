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
use Dominasys\PagBank\Charges\Response\ChargeResponse;
use Dominasys\PagBank\Environment;
use Dominasys\PagBank\Exceptions\RequestValidationException;
use Dominasys\PagBank\PagBank;
use Dominasys\PagBank\Support\Configuration;
use Dominasys\PagBank\Support\Credentials;
use Dominasys\PagBank\Support\Endpoints;
use Dominasys\PagBank\Support\Transport;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;

/**
 * @param  array<int, array<string, mixed>>  $history
 */
$makeSdkWithHistory = static function (array &$history, Response $response): PagBank {
    $mock = new MockHandler([$response]);
    $handlerStack = HandlerStack::create($mock);
    $handlerStack->push(Middleware::history($history));

    return PagBank::make(
        Configuration::make(
            endpoints: new Endpoints(environment: Environment::Sandbox),
            credentials: new Credentials(bearerToken: 'bearer-token'),
            transport: new Transport(),
        ),
        new Client(['handler' => $handlerStack]),
    );
};

it('gets charge', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'CHAR_123',
        ], JSON_THROW_ON_ERROR)),
    );

    $response = $sdk->charges()->getCharge('CHAR_123');

    Assert::assertInstanceOf(ChargeResponse::class, $response);
    Assert::assertSame('CHAR_123', $response->id());
    Assert::assertSame('GET', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/charges/CHAR_123', (string) $history[0]['request']->getUri());
});

it('captures charge', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        response: new Response(201, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'CHAR_123',
        ], JSON_THROW_ON_ERROR)),
    );

    $sdk->charges()->captureCharge(
        'CHAR_123',
        new ChargeCaptureData(
            amount: new ChargeAmountData(150099),
        ),
    );

    Assert::assertSame('POST', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/charges/CHAR_123/capture', (string) $history[0]['request']->getUri());
    Assert::assertSame([
        'amount' => [
            'value' => 150099,
            'currency' => 'BRL',
        ],
    ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
});

it('cancels charge with splits', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        response: new Response(201, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'CHAR_123',
        ], JSON_THROW_ON_ERROR)),
    );

    $sdk->charges()->cancelCharge(
        'CHAR_123',
        new ChargeCancelData(
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
        ),
    );

    Assert::assertSame('POST', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/charges/CHAR_123/cancel', (string) $history[0]['request']->getUri());
    Assert::assertSame([
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
    ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
});

it('maps charge validation errors to domain exception', function (): void {
    $sdk = PagBank::make(
        Configuration::make(
            endpoints: new Endpoints(environment: Environment::Sandbox),
            credentials: new Credentials(bearerToken: 'bearer-token'),
            transport: new Transport(),
        ),
        new Client([
            'handler' => HandlerStack::create(new MockHandler([
                new Response(400, ['Content-Type' => 'application/json'], json_encode([
                    'error' => 'invalid_request',
                ], JSON_THROW_ON_ERROR)),
            ])),
        ]),
    );

    expect(fn (): mixed => $sdk->charges()->getCharge('CHAR_123'))->toThrow(RequestValidationException::class);
});

it('charges client does not expose card tokenization', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'CHAR_123',
        ], JSON_THROW_ON_ERROR)),
    );

    Assert::assertFalse(method_exists($sdk->charges(), 'validateAndStoreCard'));
});
