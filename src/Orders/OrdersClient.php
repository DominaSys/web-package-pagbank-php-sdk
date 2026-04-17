<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Orders\Dto\CreateOrderData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentData;
use Dominasys\PagBank\Orders\Response\OrderResponse;

final readonly class OrdersClient
{
    public function __construct(
        private PagBankClient $client,
    ) {
    }

    public function createOrder(CreateOrderData $data, ?string $idempotencyKey = null): OrderResponse
    {
        $options = ['json' => $data->toArray()];

        if ($idempotencyKey !== null && $idempotencyKey !== '') {
            $options['headers'] = [
                'x-idempotency-key' => $idempotencyKey,
            ];
        }

        return OrderResponse::fromResponse($this->client->requestApi('POST', '/orders', $options));
    }

    public function getOrder(string $orderId): OrderResponse
    {
        return OrderResponse::fromResponse($this->client->requestApi('GET', '/orders/' . rawurlencode($orderId)));
    }

    public function getOrderByChargeId(string $chargeId): OrderResponse
    {
        return OrderResponse::fromResponse($this->client->requestApi('GET', '/orders', [
            'query' => [
                'charge_id' => $chargeId,
            ],
        ]));
    }

    public function payOrder(string $orderId, OrderPaymentData $data): OrderResponse
    {
        return OrderResponse::fromResponse($this->client->requestApi('POST', '/orders/' . rawurlencode($orderId) . '/pay', [
            'json' => $data->toArray(),
        ]));
    }
}
