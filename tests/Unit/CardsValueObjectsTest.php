<?php

declare(strict_types=1);

use Dominasys\PagBank\Cards\Dto\CardEncryptData;
use Dominasys\PagBank\Cards\Dto\CardHolderData;
use Dominasys\PagBank\Cards\Dto\CardStoreData;
use Dominasys\PagBank\Cards\Response\CardEncryptionError;
use Dominasys\PagBank\Cards\Response\CardEncryptionResult;
use Dominasys\PagBank\Cards\Response\CardResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\Assert;

it('store card payload converts to api shape', function (): void {
    Assert::assertSame([
        'encrypted' => 'encrypted-value',
        'holder' => [
            'name' => 'Jose da Silva',
            'tax_id' => '12345678909',
        ],
    ], CardStoreData::encrypted(
        'encrypted-value',
        new CardHolderData('Jose da Silva', '12345678909'),
    )->toArray());

    Assert::assertSame([
        'number' => '4111111111111111',
        'exp_month' => 12,
        'exp_year' => 2026,
        'security_code' => '123',
        'holder' => [
            'name' => 'Jose da Silva',
            'tax_id' => '12345678909',
        ],
    ], CardStoreData::pci(
        '4111111111111111',
        12,
        2026,
        '123',
        new CardHolderData('Jose da Silva', '12345678909'),
    )->toArray());
});

it('encrypt data carries raw fields', function (): void {
    $data = new CardEncryptData(
        publicKey: 'public-key',
        number: '4242424242424242',
        expMonth: 12,
        expYear: 2030,
        holder: 'Jose da Silva',
        securityCode: '123',
    );

    Assert::assertSame('public-key', $data->publicKey);
    Assert::assertSame('4242424242424242', $data->number);
    Assert::assertSame(12, $data->expMonth);
    Assert::assertSame(2030, $data->expYear);
    Assert::assertSame('123', $data->securityCode);
    Assert::assertSame('Jose da Silva', $data->holder);
});

it('encryption result and error shape', function (): void {
    $result = new CardEncryptionResult(
        'encrypted-value',
        [
            new CardEncryptionError('INVALID_NUMBER', 'invalid field `number`. You must pass a value between 13 and 19 digits'),
        ],
    );

    Assert::assertSame('encrypted-value', $result->encryptedCard());
    Assert::assertTrue($result->hasErrors());
    Assert::assertCount(1, $result->errors());
    Assert::assertSame('INVALID_NUMBER', $result->errors()[0]->code);
    Assert::assertSame('invalid field `number`. You must pass a value between 13 and 19 digits', $result->errors()[0]->message);
});

it('card response exposes typed accessors', function (): void {
    $response = CardResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
        200,
        ['Content-Type' => 'application/json'],
        json_encode([
            'id' => 'CARD_123',
            'encrypted' => 'encrypted-value',
            'brand' => 'visa',
            'number' => '4111111111111111',
            'network_token' => '1234567890000000',
            'exp_month' => 12,
            'exp_year' => 2026,
            'security_code' => '123',
            'store' => true,
            'product' => 'CREDIT',
            'first_digits' => 411111,
            'last_digits' => 1111,
            'holder' => [
                'name' => 'Jose da Silva',
                'tax_id' => '12345678909',
            ],
        ], JSON_THROW_ON_ERROR),
    )));

    Assert::assertSame('CARD_123', $response->id());
    Assert::assertSame('encrypted-value', $response->encrypted());
    Assert::assertSame('visa', $response->brand());
    Assert::assertSame('4111111111111111', $response->number());
    Assert::assertSame('1234567890000000', $response->networkToken());
    Assert::assertSame(12, $response->expMonth());
    Assert::assertSame(2026, $response->expYear());
    Assert::assertTrue($response->store());
    Assert::assertSame('CREDIT', $response->product());
    Assert::assertSame(411111, $response->firstDigits());
    Assert::assertSame(1111, $response->lastDigits());
    Assert::assertSame('Jose da Silva', $response->holder()?->name());
    Assert::assertSame('12345678909', $response->holder()?->taxId());
});
