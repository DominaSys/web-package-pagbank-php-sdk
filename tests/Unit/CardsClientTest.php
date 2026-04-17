<?php

declare(strict_types=1);

use Dominasys\PagBank\Cards\Dto\CardEncryptData;
use Dominasys\PagBank\Cards\Dto\CardHolderData;
use Dominasys\PagBank\Cards\Dto\CardStoreData;
use Dominasys\PagBank\Cards\Response\CardEncryptionResult;
use Dominasys\PagBank\Cards\Response\CardResponse;
use Dominasys\PagBank\Environment;
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
 * @return array{0: string, 1: string}
 */
$generateKeyPair = static function (): array {
    $privateKey = openssl_pkey_new([
        'private_key_bits' => 2048,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    Assert::assertNotFalse($privateKey);

    $exported = null;
    Assert::assertTrue(openssl_pkey_export($privateKey, $exported));

    $details = openssl_pkey_get_details($privateKey);
    Assert::assertIsArray($details);
    Assert::assertArrayHasKey('key', $details);
    Assert::assertIsString($details['key']);

    return [$details['key'], $exported];
};

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

it('encrypts card data on the backend', function () use ($generateKeyPair, $makeSdkWithHistory): void {
    $history = [];
    [$publicKey, $privateKey] = $generateKeyPair();

    $sdk = $makeSdkWithHistory(
        history: $history,
        response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'CARD_123',
        ], JSON_THROW_ON_ERROR)),
    );

    $result = $sdk->cards()->encryptCard(new CardEncryptData(
        publicKey: $publicKey,
        number: '4242424242424242',
        expMonth: 12,
        expYear: 2030,
        holder: 'José da Silva',
        securityCode: '123',
    ));

    Assert::assertInstanceOf(CardEncryptionResult::class, $result);
    Assert::assertFalse($result->hasErrors());
    Assert::assertNotNull($result->encryptedCard());
    Assert::assertCount(0, $history);

    $ciphertext = base64_decode($result->encryptedCard(), true);
    Assert::assertNotFalse($ciphertext);
    Assert::assertTrue(openssl_private_decrypt($ciphertext, $decrypted, $privateKey, OPENSSL_PKCS1_PADDING));

    Assert::assertSame('4242424242424242;123;12;2030;Jose da Silva;', preg_replace('/\d+$/', '', $decrypted));
    Assert::assertMatchesRegularExpression('/^4242424242424242;123;12;2030;Jose da Silva;\d+$/', $decrypted);
});

it('validates and stores encrypted card', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
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

    Assert::assertInstanceOf(CardResponse::class, $response);
    Assert::assertSame('CARD_123', $response->id());
    Assert::assertSame('visa', $response->brand());
    Assert::assertSame('POST', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/tokens/cards', (string) $history[0]['request']->getUri());
    Assert::assertSame([
        'encrypted' => 'encrypted-value',
        'holder' => [
            'name' => 'Jose da Silva',
            'tax_id' => '12345678909',
        ],
    ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
});

it('reports validation errors for invalid card data', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
        history: $history,
        response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'CARD_123',
        ], JSON_THROW_ON_ERROR)),
    );

    $result = $sdk->cards()->encryptCard(new CardEncryptData(
        publicKey: '',
        number: '123',
        expMonth: 0,
        expYear: 1800,
        holder: '123',
        securityCode: '12',
    ));

    Assert::assertTrue($result->hasErrors());
    Assert::assertSame([
        'INVALID_NUMBER',
        'INVALID_SECURITY_CODE',
        'INVALID_EXPIRATION_MONTH',
        'INVALID_EXPIRATION_YEAR',
        'INVALID_PUBLIC_KEY',
        'INVALID_HOLDER',
    ], array_map(static fn ($error): string => $error->code, $result->errors()));
});

it('validates and stores pci card', function () use ($makeSdkWithHistory): void {
    $history = [];

    $sdk = $makeSdkWithHistory(
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

    Assert::assertSame('POST', $history[0]['request']->getMethod());
    Assert::assertSame('https://sandbox.api.pagseguro.com/tokens/cards', (string) $history[0]['request']->getUri());
    Assert::assertSame([
        'number' => '4111111111111111',
        'exp_month' => 12,
        'exp_year' => 2026,
        'security_code' => '123',
        'holder' => [
            'name' => 'Jose da Silva',
            'tax_id' => '12345678909',
        ],
    ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
});
