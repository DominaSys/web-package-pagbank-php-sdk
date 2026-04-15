<?php

declare(strict_types=1);

use Dominasys\PagBank\Environment;
use Dominasys\PagBank\PublicKeys\Response\PublicKeyResponse;
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

final class PublicKeysClientTest extends TestCase
{
    public function testCreatesCardPublicKey(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(201, ['Content-Type' => 'text/plain'], "PUBLIC-KEY-123"),
        );

        $response = $sdk->publicKeys()->createCardPublicKey();

        self::assertInstanceOf(PublicKeyResponse::class, $response);
        self::assertSame('PUBLIC-KEY-123', $response->publicKey());
        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('https://sandbox.api.pagseguro.com/public-keys', (string) $history[0]['request']->getUri());
        self::assertSame([
            'type' => 'card',
        ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function testGetsCardPublicKey(): void
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
                'id' => 'PUBK_123',
                'type' => 'card',
                'public_key' => 'PUBLIC-KEY-123',
                'created_at' => '2026-04-14T12:00:00Z',
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->publicKeys()->getCardPublicKey();

        self::assertSame('PUBK_123', $response->id());
        self::assertSame('card', $response->type());
        self::assertSame('PUBLIC-KEY-123', $response->publicKey());
        self::assertSame('2026-04-14T12:00:00Z', $response->createdAt());
        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/public-keys/card', (string) $history[0]['request']->getUri());
    }

    public function testUpdatesCardPublicKey(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(200, ['Content-Type' => 'text/plain'], "PUBLIC-KEY-UPDATED"),
        );

        $response = $sdk->publicKeys()->updateCardPublicKey();

        self::assertSame('PUBLIC-KEY-UPDATED', $response->publicKey());
        self::assertCount(1, $history);
        self::assertSame('PUT', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/public-keys/card', (string) $history[0]['request']->getUri());
    }

    /**
     * @param  array<int, array<string, mixed>>  $history
     */
    private function makeSdkWithHistory(array &$history, Configuration $configuration, Response $response): PagBank
    {
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($history));

        return PagBank::make(
            $configuration,
            new Client(['handler' => $handlerStack]),
        );
    }
}
