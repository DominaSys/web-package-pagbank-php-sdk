<?php

declare(strict_types=1);

use Dominasys\PagBank\Client\PagBankClient;
use Dominasys\PagBank\Support\Configuration;
use Dominasys\PagBank\Support\Credentials;
use Dominasys\PagBank\Support\Transport;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;

it('does not follow redirects or forward credentials to another origin', function (): void {
    $history = [];
    $mock = new MockHandler([
        new Response(302, ['Location' => 'https://other.example/orders']),
        new Response(200),
    ]);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($history));
    $client = new PagBankClient(
        Configuration::make(credentials: new Credentials(bearerToken: 'test-token'), transport: new Transport(12, 4)),
        new Client(['handler' => $stack]),
    );

    $response = $client->requestApi('POST', '/orders', [
        'json' => ['reference_id' => 'test-order'],
        'headers' => ['x-idempotency-key' => 'test-idempotency'],
    ]);

    Assert::assertSame(302, $response->statusCode());
    Assert::assertCount(1, $history);
    Assert::assertSame(1, $mock->count());
    Assert::assertSame('Bearer test-token', $history[0]['request']->getHeaderLine('Authorization'));
    Assert::assertSame('test-idempotency', $history[0]['request']->getHeaderLine('x-idempotency-key'));
    Assert::assertSame(['reference_id' => 'test-order'], json_decode((string) $history[0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR));
    Assert::assertSame(12.0, $history[0]['options']['timeout']);
    Assert::assertSame(4.0, $history[0]['options']['connect_timeout']);
});

it('propagates connection failures without retrying a payment request', function (): void {
    $failure = new ConnectException('Connection timed out', new Request('POST', 'https://example.test/orders'));
    $mock = new MockHandler([$failure, new Response(201)]);
    $client = new PagBankClient(
        Configuration::make(credentials: new Credentials(bearerToken: 'test-token')),
        new Client(['handler' => HandlerStack::create($mock)]),
    );

    try {
        $client->requestApi('POST', '/orders', ['json' => ['reference_id' => 'test-order']]);
        Assert::fail('Expected the connection failure to propagate.');
    } catch (ConnectException $exception) {
        Assert::assertSame($failure, $exception);
    }

    Assert::assertSame(1, $mock->count());
});
