<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Charges;

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Charges\Dto\ChargeCaptureData;
use Dominasys\PagBank\Charges\Dto\ChargeCancelData;
use Dominasys\PagBank\Charges\Response\ChargeResponse;

final readonly class ChargesClient
{
    public function __construct(
        private PagBankClient $client,
    ) {
    }

    public function getCharge(string $chargeId): ChargeResponse
    {
        return ChargeResponse::fromResponse($this->client->requestApi('GET', '/charges/' . rawurlencode($chargeId)));
    }

    public function captureCharge(string $chargeId, ChargeCaptureData $data): ChargeResponse
    {
        return ChargeResponse::fromResponse($this->client->requestApi('POST', '/charges/' . rawurlencode($chargeId) . '/capture', [
            'json' => $data->toArray(),
        ]));
    }

    public function cancelCharge(string $chargeId, ChargeCancelData $data): ChargeResponse
    {
        return ChargeResponse::fromResponse($this->client->requestApi('POST', '/charges/' . rawurlencode($chargeId) . '/cancel', [
            'json' => $data->toArray(),
        ]));
    }
}
