<?php

declare(strict_types=1);

use Dominasys\PagBank\Orders\Dto\CreateOrderData;
use Dominasys\PagBank\Orders\Dto\OrderAddressData;
use Dominasys\PagBank\Orders\Dto\OrderAmountData;
use Dominasys\PagBank\Orders\Dto\OrderBoletoData;
use Dominasys\PagBank\Orders\Dto\OrderBoletoHolderData;
use Dominasys\PagBank\Orders\Dto\OrderBoletoInstructionLinesData;
use Dominasys\PagBank\Orders\Dto\OrderCardData;
use Dominasys\PagBank\Orders\Dto\OrderCardHolderData;
use Dominasys\PagBank\Orders\Dto\OrderChargeData;
use Dominasys\PagBank\Orders\Dto\OrderCustomerData;
use Dominasys\PagBank\Orders\Dto\OrderItemData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentMethodData;
use Dominasys\PagBank\Orders\Dto\OrderPhoneData;
use Dominasys\PagBank\Orders\Dto\OrderQrCodeAmountData;
use Dominasys\PagBank\Orders\Dto\OrderQrCodeData;
use Dominasys\PagBank\Orders\Dto\OrderShippingData;
use Dominasys\PagBank\Orders\Dto\OrderWalletData;
use Dominasys\PagBank\Orders\Enums\OrderCustomerPhoneType;
use Dominasys\PagBank\Orders\Enums\OrderPaymentMethodType;
use Dominasys\PagBank\Orders\Enums\OrderWalletType;
use Dominasys\PagBank\Orders\Response\OrderChargeResponse;
use Dominasys\PagBank\Orders\Response\OrderCustomerResponse;
use Dominasys\PagBank\Orders\Response\OrderItemResponse;
use Dominasys\PagBank\Orders\Response\OrderLinkResponse;
use Dominasys\PagBank\Orders\Response\OrderPhoneResponse;
use Dominasys\PagBank\Orders\Response\OrderResponse;
use Dominasys\PagBank\Orders\Response\OrderShippingResponse;
use Dominasys\PagBank\Orders\Response\OrderQrCodeResponse;
use Dominasys\PagBank\Orders\Response\OrderAmountResponse;
use Dominasys\PagBank\Orders\Response\OrderPaymentMethodResponse;
use Dominasys\PagBank\Orders\Response\OrderCardResponse;
use Dominasys\PagBank\Orders\Response\OrderBoletoResponse;
use Dominasys\PagBank\Orders\Response\OrderCardHolderResponse;
use Dominasys\PagBank\Orders\Response\OrderWalletResponse;
use Dominasys\PagBank\Orders\Response\OrderAmountSummaryResponse;
use Dominasys\PagBank\Orders\Response\OrderPaymentResponse;
use Dominasys\PagBank\Orders\Response\OrderPaymentRawDataResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

final class OrdersValueObjectsTest extends TestCase
{
    public function testCreateOrderDataConvertsToApiPayload(): void
    {
        $data = new CreateOrderData(
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
            notificationUrls: ['https://meusite.com/notificacoes'],
            qrCodes: [
                new OrderQrCodeData(
                    amount: new OrderQrCodeAmountData(500),
                    expirationDate: '2021-08-29T20:15:59-03:00',
                ),
            ],
            charges: [
                new OrderChargeData(
                    referenceId: 'MY-ID-123',
                    description: 'Motivo de pagamento',
                    amount: new OrderAmountData(1000),
                    paymentMethod: new OrderPaymentMethodData(
                        type: OrderPaymentMethodType::CreditCard,
                        installments: 1,
                        capture: true,
                        softDescriptor: 'Loja do meu teste',
                        card: new OrderCardData(
                            number: '4111111111111111',
                            expMonth: 3,
                            expYear: 2026,
                            securityCode: '123',
                            holder: new OrderCardHolderData('Jose da Silva', '65544332211'),
                            wallet: new OrderWalletData(OrderWalletType::GooglePay, '{"signature":"..."}'),
                        ),
                    ),
                ),
            ],
        );

        self::assertSame([
            'reference_id' => 'ex-00001',
            'customer' => [
                'tax_id' => '12345678909',
                'name' => 'Jose da Silva',
                'email' => 'email@test.com',
                'phones' => [
                    [
                        'country' => '55',
                        'area' => '11',
                        'number' => '999999999',
                        'type' => 'MOBILE',
                    ],
                ],
            ],
            'items' => [
                [
                    'reference_id' => 'referencia do item',
                    'name' => 'nome do item',
                    'quantity' => 1,
                    'unit_amount' => 500,
                ],
            ],
            'shipping' => [
                'address' => [
                    'street' => 'Avenida Brigadeiro Faria Lima',
                    'number' => '1384',
                    'locality' => 'Pinheiros',
                    'city' => 'São Paulo',
                    'region_code' => 'SP',
                    'country' => 'BRA',
                    'postal_code' => '01452002',
                    'complement' => 'apto 12',
                ],
            ],
            'notification_urls' => ['https://meusite.com/notificacoes'],
            'qr_codes' => [
                [
                    'amount' => [
                        'value' => 500,
                    ],
                    'expiration_date' => '2021-08-29T20:15:59-03:00',
                ],
            ],
            'charges' => [
                [
                    'reference_id' => 'MY-ID-123',
                    'description' => 'Motivo de pagamento',
                    'amount' => [
                        'value' => 1000,
                        'currency' => 'BRL',
                    ],
                    'payment_method' => [
                        'type' => 'CREDIT_CARD',
                        'installments' => 1,
                        'capture' => true,
                        'soft_descriptor' => 'Loja do meu teste',
                        'card' => [
                            'number' => '4111111111111111',
                            'exp_month' => 3,
                            'exp_year' => 2026,
                            'security_code' => '123',
                            'holder' => [
                                'name' => 'Jose da Silva',
                                'tax_id' => '65544332211',
                            ],
                            'wallet' => [
                                'type' => 'GOOGLE_PAY',
                                'key' => '{"signature":"..."}',
                            ],
                        ],
                    ],
                ],
            ],
        ], $data->toArray());

        $payment = new OrderPaymentData([
            new OrderChargeData(
                referenceId: 'pay-1',
                description: 'Pedido pago',
                amount: new OrderAmountData(500),
                paymentMethod: new OrderPaymentMethodData(
                    type: OrderPaymentMethodType::Boleto,
                    boleto: new OrderBoletoData(
                        dueDate: '2025-10-20',
                        instructionLines: new OrderBoletoInstructionLinesData('Linha 1', 'Linha 2'),
                        holder: new OrderBoletoHolderData('Joao Silva', '07578643096', 'email@gmail.com', new OrderAddressData(
                            street: 'RUA DOUTOR ANTONIO BENTO',
                            number: '123',
                            complement: null,
                            locality: 'Santo Amaro',
                            city: 'São Paulo',
                            regionCode: 'SP',
                            country: 'BRA',
                            postalCode: '04750001',
                            region: 'SP',
                        )),
                    ),
                ),
            ),
        ]);

        self::assertSame([
            'charges' => [
                [
                    'reference_id' => 'pay-1',
                    'description' => 'Pedido pago',
                    'amount' => [
                        'value' => 500,
                        'currency' => 'BRL',
                    ],
                    'payment_method' => [
                        'type' => 'BOLETO',
                        'boleto' => [
                            'due_date' => '2025-10-20',
                            'instruction_lines' => [
                                'line_1' => 'Linha 1',
                                'line_2' => 'Linha 2',
                            ],
                            'holder' => [
                                'name' => 'Joao Silva',
                                'tax_id' => '07578643096',
                                'email' => 'email@gmail.com',
                                'address' => [
                                    'street' => 'RUA DOUTOR ANTONIO BENTO',
                                    'number' => '123',
                                    'locality' => 'Santo Amaro',
                                    'city' => 'São Paulo',
                                    'region_code' => 'SP',
                                    'country' => 'BRA',
                                    'postal_code' => '04750001',
                                    'region' => 'SP',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $payment->toArray());
    }

    public function testOrderResponseExposesTypedAccessors(): void
    {
        $response = OrderResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
            201,
            ['Content-Type' => 'application/json'],
            json_encode([
                'id' => 'ORDE_F87334AC-BB8B-42E2-AA85-8579F70AA328',
                'reference_id' => 'ex-00001',
                'created_at' => '2020-11-21T23:23:22.69-03:00',
                'customer' => [
                    'name' => 'Jose da Silva',
                    'email' => 'email@test.com',
                    'tax_id' => '12345678909',
                    'phones' => [
                        [
                            'type' => 'MOBILE',
                            'country' => '55',
                            'area' => '11',
                            'number' => '999999999',
                        ],
                    ],
                ],
                'items' => [
                    [
                        'reference_id' => 'referencia do item',
                        'name' => 'nome do item',
                        'quantity' => 1,
                        'unit_amount' => 500,
                    ],
                ],
                'shipping' => [
                    'address' => [
                        'street' => 'Avenida Brigadeiro Faria Lima',
                        'number' => '1384',
                        'complement' => 'apto 12',
                        'locality' => 'Pinheiros',
                        'city' => 'São Paulo',
                        'region_code' => 'SP',
                        'country' => 'BRA',
                        'postal_code' => '01452002',
                    ],
                ],
                'qr_codes' => [
                    [
                        'id' => 'QRCO_1',
                        'expiration_date' => '2021-08-29T20:15:59-03:00',
                        'amount' => ['value' => 500],
                        'text' => '000201...',
                        'links' => [
                            [
                                'rel' => 'QRCODE.PNG',
                                'href' => 'https://api.pagseguro.com/qrcode/QRCO_1/png',
                                'media' => 'image/png',
                                'type' => 'GET',
                            ],
                        ],
                    ],
                ],
                'charges' => [
                    [
                        'id' => 'CHAR_1',
                        'status' => 'PAID',
                        'created_at' => '2023-02-08T17:44:35.385-03:00',
                        'paid_at' => '2023-02-08T17:44:35.000-03:00',
                        'reference_id' => 'MY-ID-123',
                        'description' => 'Motivo de pagamento',
                        'amount' => [
                            'value' => 1000,
                            'currency' => 'BRL',
                            'summary' => [
                                'total' => 1000,
                                'paid' => 1000,
                                'refunded' => 0,
                            ],
                        ],
                        'payment_response' => [
                            'code' => '20000',
                            'message' => 'SUCESSO',
                            'reference' => '032416400102',
                            'raw_data' => [
                                'authorization_code' => '004581',
                                'nsu' => '032416400102',
                                'reason_code' => '00',
                            ],
                        ],
                        'payment_method' => [
                            'type' => 'CREDIT_CARD',
                            'installments' => 1,
                            'capture' => true,
                            'card' => [
                                'brand' => 'visa',
                                'first_digits' => 411111,
                                'last_digits' => 1111,
                                'exp_month' => 12,
                                'exp_year' => 2026,
                                'holder' => [
                                    'name' => 'Jose da Silva',
                                    'tax_id' => '65544332211',
                                ],
                            ],
                        ],
                    ],
                ],
                'links' => [
                    [
                        'rel' => 'SELF',
                        'href' => 'https://api.pagseguro.com/orders/ORDE_F87334AC-BB8B-42E2-AA85-8579F70AA328',
                        'media' => 'application/json',
                        'type' => 'GET',
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
        )));

        self::assertSame('ORDE_F87334AC-BB8B-42E2-AA85-8579F70AA328', $response->id());
        self::assertSame('ex-00001', $response->referenceId());
        self::assertSame('2020-11-21T23:23:22.69-03:00', $response->createdAt());
        self::assertCount(1, $response->items());
        self::assertInstanceOf(OrderItemResponse::class, $response->items()[0]);
        self::assertSame('nome do item', $response->items()[0]->name());
        self::assertInstanceOf(OrderCustomerResponse::class, $response->customer());
        self::assertSame('Jose da Silva', $response->customer()?->name());
        self::assertCount(1, $response->customer()?->phones() ?? []);
        self::assertInstanceOf(OrderPhoneResponse::class, $response->customer()?->phones()[0] ?? null);
        self::assertInstanceOf(OrderShippingResponse::class, $response->shipping());
        self::assertSame('Avenida Brigadeiro Faria Lima', $response->shipping()?->address()?->street());
        self::assertCount(1, $response->qrCodes());
        self::assertInstanceOf(OrderQrCodeResponse::class, $response->qrCodes()[0]);
        self::assertSame('QRCO_1', $response->qrCodes()[0]->id());
        self::assertInstanceOf(OrderChargeResponse::class, $response->charges()[0] ?? null);
        self::assertSame('PAID', $response->charges()[0]->status());
        self::assertInstanceOf(OrderAmountResponse::class, $response->charges()[0]->amount());
        self::assertSame(1000, $response->charges()[0]->amount()?->summary()?->paid());
        self::assertInstanceOf(OrderPaymentMethodResponse::class, $response->charges()[0]->paymentMethod());
        self::assertSame('CREDIT_CARD', $response->charges()[0]->paymentMethod()?->type());
        self::assertInstanceOf(OrderCardResponse::class, $response->charges()[0]->paymentMethod()?->card());
        self::assertSame('visa', $response->charges()[0]->paymentMethod()?->card()?->brand());
        self::assertInstanceOf(OrderPaymentResponse::class, $response->charges()[0]->paymentResponse());
        self::assertSame(20000, $response->charges()[0]->paymentResponse()?->code());
        self::assertInstanceOf(OrderLinkResponse::class, $response->links()[0] ?? null);
        self::assertSame('SELF', $response->links()[0]->rel());
        self::assertSame(201, $response->statusCode());
    }
}
