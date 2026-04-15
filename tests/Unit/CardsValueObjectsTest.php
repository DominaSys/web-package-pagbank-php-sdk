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
use PHPUnit\Framework\TestCase;

final class CardsValueObjectsTest extends TestCase
{
    public function testStoreCardPayloadConvertsToApiShape(): void
    {
        self::assertSame([
            'encrypted' => 'encrypted-value',
            'holder' => [
                'name' => 'Jose da Silva',
                'tax_id' => '12345678909',
            ],
        ], CardStoreData::encrypted(
            'encrypted-value',
            new CardHolderData('Jose da Silva', '12345678909'),
        )->toArray());

        self::assertSame([
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
    }

    public function testEncryptDataCarriesRawFields(): void
    {
        $data = new CardEncryptData(
            publicKey: 'public-key',
            number: '4242424242424242',
            expMonth: 12,
            expYear: 2030,
            holder: 'Jose da Silva',
            securityCode: '123',
        );

        self::assertSame('public-key', $data->publicKey);
        self::assertSame('4242424242424242', $data->number);
        self::assertSame(12, $data->expMonth);
        self::assertSame(2030, $data->expYear);
        self::assertSame('123', $data->securityCode);
        self::assertSame('Jose da Silva', $data->holder);
    }

    public function testEncryptionResultAndErrorShape(): void
    {
        $result = new CardEncryptionResult(
            'encrypted-value',
            [
                new CardEncryptionError('INVALID_NUMBER', 'invalid field `number`. You must pass a value between 13 and 19 digits'),
            ],
        );

        self::assertSame('encrypted-value', $result->encryptedCard());
        self::assertTrue($result->hasErrors());
        self::assertCount(1, $result->errors());
        self::assertSame('INVALID_NUMBER', $result->errors()[0]->code);
        self::assertSame('invalid field `number`. You must pass a value between 13 and 19 digits', $result->errors()[0]->message);
    }

    public function testCardResponseExposesTypedAccessors(): void
    {
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

        self::assertSame('CARD_123', $response->id());
        self::assertSame('encrypted-value', $response->encrypted());
        self::assertSame('visa', $response->brand());
        self::assertSame('4111111111111111', $response->number());
        self::assertSame('1234567890000000', $response->networkToken());
        self::assertSame(12, $response->expMonth());
        self::assertSame(2026, $response->expYear());
        self::assertTrue($response->store());
        self::assertSame('CREDIT', $response->product());
        self::assertSame(411111, $response->firstDigits());
        self::assertSame(1111, $response->lastDigits());
        self::assertSame('Jose da Silva', $response->holder()?->name());
        self::assertSame('12345678909', $response->holder()?->taxId());
    }
}
