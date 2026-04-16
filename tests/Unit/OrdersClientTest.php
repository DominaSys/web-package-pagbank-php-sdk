<?php

declare(strict_types=1);

use Dominasys\PagBank\Environment;
use Dominasys\PagBank\Orders\Dto\CreateOrderData;
use Dominasys\PagBank\Orders\Dto\OrderAddressData;
use Dominasys\PagBank\Orders\Dto\OrderAmountData;
use Dominasys\PagBank\Orders\Dto\OrderCardData;
use Dominasys\PagBank\Orders\Dto\OrderCardHolderData;
use Dominasys\PagBank\Orders\Dto\OrderChargeData;
use Dominasys\PagBank\Orders\Dto\OrderCustomerData;
use Dominasys\PagBank\Orders\Dto\OrderItemData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentMethodData;
use Dominasys\PagBank\Orders\Dto\OrderPhoneData;
use Dominasys\PagBank\Orders\Enums\OrderCustomerPhoneType;
use Dominasys\PagBank\Orders\Enums\OrderPaymentMethodType;
use Dominasys\PagBank\Orders\Dto\OrderShippingData;
use Dominasys\PagBank\Exceptions\RequestValidationException;
use Dominasys\PagBank\Support\Configuration;
use Dominasys\PagBank\Support\Credentials;
use Dominasys\PagBank\Support\Endpoints;
use Dominasys\PagBank\Support\Transport;
use Dominasys\PagBank\PagBank;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OrdersClientTest extends TestCase
{
    public function testCreatesOrderWithIdempotencyKey(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'ORDE_123',
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->orders()->createOrder(
            new CreateOrderData(
                referenceId: 'ex-00001',
                customer: new OrderCustomerData(
                    name: 'Jose da Silva',
                    email: 'email@test.com',
                    taxId: '12345678909',
                    phones: [
                        new OrderPhoneData('55', '11', '999999999', OrderCustomerPhoneType::Mobile),
                    ],
                ),
                items: [
                    new OrderItemData('referencia do item', 'nome do item', 1, 500),
                ],
                shipping: new OrderShippingData(
                    new OrderAddressData(
                        street: 'Avenida Brigadeiro Faria Lima',
                        number: '1384',
                        complement: 'apto 12',
                        locality: 'Pinheiros',
                        city: 'São Paulo',
                        regionCode: 'SP',
                        country: 'BRA',
                        postalCode: '01452002',
                    ),
                ),
            ),
            'idem-123',
        );

        self::assertSame('ORDE_123', $response->id());
        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('idem-123', $history[0]['request']->getHeaderLine('x-idempotency-key'));
        self::assertSame('https://sandbox.api.pagseguro.com/orders', (string) $history[0]['request']->getUri());
    }

    public function testGetsOrderAndOrderByChargeId(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'ORDE_123',
            ], JSON_THROW_ON_ERROR)),
            additionalResponses: [
                new Response(200, ['Content-Type' => 'application/json'], json_encode([
                    'id' => 'ORDE_456',
                ], JSON_THROW_ON_ERROR)),
            ],
        );

        self::assertSame('ORDE_123', $sdk->orders()->getOrder('ORDE_123')->id());
        self::assertSame('ORDE_456', $sdk->orders()->getOrderByChargeId('CHAR_123')->id());

        self::assertCount(2, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/orders/ORDE_123', (string) $history[0]['request']->getUri());
        self::assertSame('https://sandbox.api.pagseguro.com/orders?charge_id=CHAR_123', (string) $history[1]['request']->getUri());
    }

    public function testPaysOrder(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'ORDE_123',
            ], JSON_THROW_ON_ERROR)),
        );

        $sdk->orders()->payOrder(
            'ORDE_123',
            new OrderPaymentData([
                new OrderChargeData(
                    referenceId: 'pay-1',
                    description: 'Pedido pago',
                    amount: new OrderAmountData(500),
                    paymentMethod: new OrderPaymentMethodData(
                        type: OrderPaymentMethodType::Boleto,
                    ),
                ),
            ]),
        );

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/orders/ORDE_123/pay', (string) $history[0]['request']->getUri());
    }

    public function testCancelsOrder(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'ORDE_123',
            ], JSON_THROW_ON_ERROR)),
        );

        $sdk->orders()->cancelOrder('ORDE_123');

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/orders/ORDE_123/cancel', (string) $history[0]['request']->getUri());
    }

    public function testMapsOrderValidationErrorsToDomainException(): void
    {
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

        $this->expectException(RequestValidationException::class);

        $sdk->orders()->createOrder(
            new CreateOrderData(
                customer: new OrderCustomerData(
                    name: 'Jose da Silva',
                    email: 'email@test.com',
                    taxId: '12345678909',
                ),
                items: [
                    new OrderItemData('ref', 'item', 1, 500),
                ],
            ),
        );
    }

    /**
     * @param  array<int, Response>  $additionalResponses
     * @param  array<int, array<string, mixed>>  $history
     */
    private function makeSdkWithHistory(array &$history, Configuration $configuration, Response $response, array $additionalResponses = []): PagBank
    {
        $mock = new MockHandler(array_merge([$response], $additionalResponses));
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($history));

        return PagBank::make(
            $configuration,
            new Client(['handler' => $handlerStack]),
        );
    }
}
