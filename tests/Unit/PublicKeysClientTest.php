<?php

declare(strict_types=1);

use Dominasys\PagBank\Environment;
use Dominasys\PagBank\PagBank;
use Dominasys\PagBank\PublicKeys\Response\PublicKeyResponse;
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
$makeSdkWithHistory = static function (array &$history, Configuration $configuration, Response $response): PagBank {
    $mock = new MockHandler([$response]);
    $handlerStack = HandlerStack::create($mock);
    $handlerStack->push(Middleware::history($history));

    return PagBank::make(
        $configuration,
        new Client(['handler' => $handlerStack]),
    );
};

it('creates card public key', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        configuration: Configuration::make(
            endpoints: new Endpoints(environment: Environment::Sandbox),
            credentials: new Credentials(bearerToken: 'bearer-token'),
            transport: new Transport(),
        ),
        response: new Response(201, ['Content-Type' => 'text/plain'], "PUBLIC-KEY-123"),
    );

    $response = $sdk->publicKeys()->createCardPublicKey();

    Assert::assertInstanceOf(PublicKeyResponse::class, $response);
    Assert::assertSame('PUBLIC-KEY-123', $response->publicKey());
    Assert::assertCount(1, $history);
    Assert::assertSame('POST', $history[0]['request']->getMethod());
    Assert::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
    Assert::assertSame('https://sandbox.api.pagseguro.com/public-keys', (string) $history[0]['request']->getUri());
    Assert::assertSame([
        'type' => 'card',
    ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
});

it('gets card public key', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        configuration: Configuration::make(
            endpoints: new Endpoints(environment: Environment::Sandbox),
            credentials: new Credentials(bearerToken: 'bearer-token'),
            transport: new Transport(),
        ),
        response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'PUBK_123',
            'type' => 'card',
            'public_key' => 'PUBLIC-KEY-123',
            'created_at' => '2026-04-14T12:00:00Z',
        ], JSON_THROW_ON_ERROR)),
    );

    $response = $sdk->publicKeys()->getCardPublicKey();

    Assert::assertSame('PUBK_123', $response->id());
    Assert::assertSame('card', $response->type());
    Assert::assertSame('PUBLIC-KEY-123', $response->publicKey());
    Assert::assertSame('2026-04-14T12:00:00Z', $response->createdAt());
    Assert::assertCount(1, $history);
    Assert::assertSame('GET', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/public-keys/card', (string) $history[0]['request']->getUri());
});

it('updates card public key', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        configuration: Configuration::make(
            endpoints: new Endpoints(environment: Environment::Sandbox),
            credentials: new Credentials(bearerToken: 'bearer-token'),
            transport: new Transport(),
        ),
        response: new Response(200, ['Content-Type' => 'text/plain'], "PUBLIC-KEY-UPDATED"),
    );

    $response = $sdk->publicKeys()->updateCardPublicKey();

    Assert::assertSame('PUBLIC-KEY-UPDATED', $response->publicKey());
    Assert::assertCount(1, $history);
    Assert::assertSame('PUT', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/public-keys/card', (string) $history[0]['request']->getUri());
});
