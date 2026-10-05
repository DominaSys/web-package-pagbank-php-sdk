<?php

use Dominasys\PagBank\Orders\Dto\OrderAmountData;
use Dominasys\PagBank\Orders\Dto\OrderAuthenticationMethodData;
use Dominasys\PagBank\Orders\Dto\OrderBuyerInterestData;
use Dominasys\PagBank\Orders\Dto\OrderCardData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentData;
use Dominasys\PagBank\Orders\Dto\OrderPaymentMethodData;
use Dominasys\PagBank\Orders\Dto\OrderPixData;
use Dominasys\PagBank\Orders\Enums\OrderPaymentMethodType;
use Dominasys\PagBank\PagBank;
use Dominasys\PagBank\Support\Configuration;
use Dominasys\PagBank\Support\Credentials;
use Dominasys\PagBank\Support\WebhookSignature;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

it('uses the SDK host for sessions and API host for fees', function () {
    $history = [];
    $handler = HandlerStack::create(new MockHandler([new Response(201, [], '{"session":"test"}'), new Response(200, [], '{}')]));
    $handler->push(Middleware::history($history));
    $sdk = PagBank::make(Configuration::make(credentials: new Credentials(bearerToken: 'test')), new Client(['handler' => $handler]));
    expect($sdk->checkout()->createSession()->json()['session'])->toBe('test');
    $sdk->checkout()->calculateFees(13200, '524008', 6);
    expect((string) $history[0]['request']->getUri())->toBe('https://sandbox.sdk.pagseguro.com/checkout-sdk/sessions');
    parse_str($history[1]['request']->getUri()->getQuery(), $query);
    expect($query['max_installments_no_interest'])->toBe('0')->and($query['value'])->toBe('13200');
    expect(fn () => $sdk->checkout()->calculateFees(13200, '524008', 6, 1))->toThrow(InvalidArgumentException::class);
});

it('serializes current Pix, buyer fees and 3DS outside the card', function () {
    $auth = new OrderAuthenticationMethodData('THREEDS', '3DS_test');
    $method = new OrderPaymentMethodData(OrderPaymentMethodType::CreditCard, card: new OrderCardData(encrypted: 'encrypted', authenticationMethod: $auth));
    expect($method->toArray()['authentication_method']['id'])->toBe('3DS_test');
    expect($method->toArray()['card'])->not->toHaveKey('authentication_method');
    expect((new OrderAmountData(14858, buyerInterest: new OrderBuyerInterestData(1658, 6)))->toArray()['fees']['buyer']['interest'])->toBe(['total' => 1658, 'installments' => 6]);
    expect((new OrderPaymentMethodData(OrderPaymentMethodType::Pix, pix: new OrderPixData('2030-01-01T00:00:00Z')))->toArray()['pix']['expiration_date'])->toBe('2030-01-01T00:00:00Z');
});

it('validates signatures against the exact raw body', function () {
    $body = '{"id":"ORDE_test"}';
    $signature = hash('sha256', 'secret-' . $body);
    expect(WebhookSignature::verify($body, $signature, 'secret'))->toBeTrue();
    expect(WebhookSignature::verify($body . ' ', $signature, 'secret'))->toBeFalse();
    expect(WebhookSignature::verify($body, $signature, ''))->toBeFalse();
});

it('uses an idempotency key when paying an existing order', function () {
    $history = [];
    $handler = HandlerStack::create(new MockHandler([new Response(201, [], '{"id":"ORDE_test"}'), new Response(200, [], '{"id":"CHAR_test"}')]));
    $handler->push(Middleware::history($history));
    $sdk = PagBank::make(Configuration::make(credentials: new Credentials(bearerToken: 'test')), new Client(['handler' => $handler]));
    $sdk->orders()->payOrder('ORDE_test', new OrderPaymentData([]), 'safe-attempt');
    $sdk->charges()->getCharge('CHAR_test');
    expect($history[0]['request']->getHeaderLine('x-idempotency-key'))->toBe('safe-attempt')
        ->and($history[1]['request']->getHeaderLine('Accept'))->toBe('*/*');
});
