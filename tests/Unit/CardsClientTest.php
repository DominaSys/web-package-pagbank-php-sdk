<?php

declare(strict_types=1);

use Dominasys\PagBank\Cards\Dto\CardHolderData;
use Dominasys\PagBank\Cards\Dto\CardStoreData;
use Dominasys\PagBank\Cards\Response\CardResponse;
use Dominasys\PagBank\Environment;
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

final class CardsClientTest extends TestCase
{
    public function testValidatesAndStoresEncryptedCard(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'CARD_123',
                'brand' => 'visa',
                'first_digits' => 411111,
                'last_digits' => 1111,
                'exp_month' => 12,
                'exp_year' => 2026,
                'store' => true,
                'holder' => [
                    'name' => 'Jose da Silva',
                    'tax_id' => '12345678909',
                ],
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->cards()->validateAndStoreCard(
            CardStoreData::encrypted(
                'encrypted-value',
                new CardHolderData('Jose da Silva', '12345678909'),
            ),
        );

        self::assertInstanceOf(CardResponse::class, $response);
        self::assertSame('CARD_123', $response->id());
        self::assertSame('visa', $response->brand());
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/tokens/cards', (string) $history[0]['request']->getUri());
        self::assertSame([
            'encrypted' => 'encrypted-value',
            'holder' => [
                'name' => 'Jose da Silva',
                'tax_id' => '12345678909',
            ],
        ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function testValidatesAndStoresPciCard(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'CARD_456',
                'brand' => 'visa',
                'first_digits' => 411111,
                'last_digits' => 1111,
                'exp_month' => 12,
                'exp_year' => 2026,
                'store' => true,
            ], JSON_THROW_ON_ERROR)),
        );

        $sdk->cards()->validateAndStoreCard(
            CardStoreData::pci(
                '4111111111111111',
                12,
                2026,
                '123',
                new CardHolderData('Jose da Silva', '12345678909'),
            ),
        );

        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('https://sandbox.api.pagseguro.com/tokens/cards', (string) $history[0]['request']->getUri());
        self::assertSame([
            'number' => '4111111111111111',
            'exp_month' => 12,
            'exp_year' => 2026,
            'security_code' => '123',
            'holder' => [
                'name' => 'Jose da Silva',
                'tax_id' => '12345678909',
            ],
        ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * @param  array<int, array<string, mixed>>  $history
     */
    private function makeSdkWithHistory(array &$history, Response $response): PagBank
    {
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
    }
}
