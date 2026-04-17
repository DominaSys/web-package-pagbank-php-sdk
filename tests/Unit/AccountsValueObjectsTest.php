<?php

declare(strict_types=1);

use Dominasys\PagBank\Accounts\Dto\AccountAddressData;
use Dominasys\PagBank\Accounts\Dto\AccountCompanyData;
use Dominasys\PagBank\Accounts\Dto\AccountData;
use Dominasys\PagBank\Accounts\Dto\AccountPersonData;
use Dominasys\PagBank\Accounts\Dto\AccountPhoneData;
use Dominasys\PagBank\Accounts\Dto\AccountTosAcceptanceData;
use Dominasys\PagBank\Accounts\Enums\AccountPhoneType;
use Dominasys\PagBank\Accounts\Enums\AccountType;
use Dominasys\PagBank\Accounts\Response\AccountAddressResponse;
use Dominasys\PagBank\Accounts\Response\AccountCompanyResponse;
use Dominasys\PagBank\Accounts\Response\AccountPersonResponse;
use Dominasys\PagBank\Accounts\Response\AccountPhoneResponse;
use Dominasys\PagBank\Accounts\Response\AccountResponse;
use Dominasys\PagBank\Accounts\Response\AccountTosAcceptanceResponse;
use Dominasys\PagBank\Accounts\Response\AccountTokenResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\Assert;

it('account create dto converts to api payload', function (): void {
    $data = AccountData::seller(
        email: 'contato@domina.example',
        businessCategory: 'RESTAURANT',
        person: new AccountPersonData(
            name: 'José Carlos Silva',
            birthDate: '1991-10-10',
            motherName: 'Maria Silva',
            taxId: '12345678900',
            address: new AccountAddressData(
                street: 'Rua A',
                number: '100',
                complement: 'Sala 1',
                locality: 'Centro',
                city: 'São Paulo',
                regionCode: 'BR-SP',
                country: 'BRA',
                postalCode: '01000-000',
            ),
            phones: [
                new AccountPhoneData(
                    country: '55',
                    area: '11',
                    number: '999999999',
                    type: AccountPhoneType::Mobile,
                ),
            ],
        ),
        company: new AccountCompanyData(
            name: 'DominaSys LTDA',
            taxId: '12345678000100',
            address: new AccountAddressData(
                street: 'Av. Paulista',
                number: '1000',
                complement: null,
                locality: 'Bela Vista',
                city: 'São Paulo',
                regionCode: 'BR-SP',
                country: 'BRA',
                postalCode: '01310-100',
            ),
        ),
        tosAcceptance: new AccountTosAcceptanceData(
            userIp: '127.0.0.1',
            date: '2024-01-01T10:00:00-03:00',
        ),
    );

    Assert::assertSame([
        'type' => 'SELLER',
        'email' => 'contato@domina.example',
        'person' => [
            'name' => 'José Carlos Silva',
            'birth_date' => '1991-10-10',
            'mother_name' => 'Maria Silva',
            'tax_id' => '12345678900',
            'address' => [
                'street' => 'Rua A',
                'number' => '100',
                'locality' => 'Centro',
                'city' => 'São Paulo',
                'region_code' => 'BR-SP',
                'country' => 'BRA',
                'postal_code' => '01000-000',
                'complement' => 'Sala 1',
            ],
            'phones' => [
                [
                    'country' => '55',
                    'area' => '11',
                    'number' => '999999999',
                    'type' => 'MOBILE',
                ],
            ],
        ],
        'tos_acceptance' => [
            'user_ip' => '127.0.0.1',
            'date' => '2024-01-01T10:00:00-03:00',
        ],
        'business_category' => 'RESTAURANT',
        'company' => [
            'name' => 'DominaSys LTDA',
            'tax_id' => '12345678000100',
            'address' => [
                'street' => 'Av. Paulista',
                'number' => '1000',
                'locality' => 'Bela Vista',
                'city' => 'São Paulo',
                'region_code' => 'BR-SP',
                'country' => 'BRA',
                'postal_code' => '01310-100',
            ],
        ],
    ], $data->toArray());
});

it('account response exposes typed accessors', function (): void {
    $response = AccountResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
        200,
        ['Content-Type' => 'application/json'],
        json_encode([
            'id' => 'ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36',
            'type' => 'SELLER',
            'status' => 'ACTIVE',
            'created_at' => '2021-07-23T15:17:12.115601-03:00',
            'email' => 'contato@domina.example',
            'business_category' => 'RESTAURANT',
            'account_advanced' => true,
            'person' => [
                'birth_date' => '1991-10-10',
                'name' => 'José Carlos Silva',
                'tax_id' => '12345678900',
                'mother_name' => 'Maria Silva',
                'address' => [
                    'region_code' => 'BR-SP',
                    'city' => 'São Paulo',
                    'postal_code' => '01000-000',
                    'street' => 'Rua A',
                    'number' => '100',
                    'complement' => 'Sala 1',
                    'locality' => 'Centro',
                    'country' => 'BRA',
                ],
                'phones' => [
                    [
                        'country' => '55',
                        'area' => '11',
                        'number' => '999999999',
                        'type' => 'MOBILE',
                    ],
                ],
            ],
            'company' => [
                'name' => 'DominaSys LTDA',
                'trade_name' => 'DominaSys',
                'tax_id' => '12345678000100',
                'address' => [
                    'region_code' => 'BR-SP',
                    'city' => 'São Paulo',
                    'postal_code' => '01310-100',
                    'street' => 'Av. Paulista',
                    'number' => '1000',
                    'complement' => null,
                    'locality' => 'Bela Vista',
                ],
                'phones' => [
                    [
                        'country' => '55',
                        'area' => '11',
                        'number' => '988888888',
                    ],
                ],
            ],
            'tos_acceptance' => [
                'user_ip' => '127.0.0.1',
                'date' => '2024-01-01T10:00:00-03:00',
            ],
            'token' => [
                'refresh_token' => 'refresh-123',
                'token_type' => 'Bearer',
                'access_token' => 'access-123',
                'expires_in' => 3600,
                'scope' => 'accounts.read payments.create',
            ],
        ], JSON_THROW_ON_ERROR),
    )));

    Assert::assertSame('ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36', $response->id());
    Assert::assertSame(AccountType::Seller, $response->type());
    Assert::assertSame('ACTIVE', $response->status());
    Assert::assertSame('2021-07-23T15:17:12.115601-03:00', $response->createdAt());
    Assert::assertSame('contato@domina.example', $response->email());
    Assert::assertSame('RESTAURANT', $response->businessCategory());
    Assert::assertTrue($response->accountAdvanced());
    Assert::assertSame(200, $response->statusCode());

    $person = $response->person();
    Assert::assertInstanceOf(AccountPersonResponse::class, $person);
    Assert::assertSame('José Carlos Silva', $person?->name());
    Assert::assertSame('1991-10-10', $person?->birthDate());
    Assert::assertSame('Maria Silva', $person?->motherName());
    Assert::assertSame('12345678900', $person?->taxId());
    Assert::assertInstanceOf(AccountAddressResponse::class, $person?->address());
    Assert::assertSame('São Paulo', $person?->address()?->city());
    Assert::assertCount(1, $person?->phones() ?? []);
    Assert::assertInstanceOf(AccountPhoneResponse::class, $person?->phones()[0] ?? null);
    Assert::assertSame(AccountPhoneType::Mobile, $person?->phones()[0]->type());

    $company = $response->company();
    Assert::assertInstanceOf(AccountCompanyResponse::class, $company);
    Assert::assertSame('DominaSys LTDA', $company?->name());
    Assert::assertSame('DominaSys', $company?->tradeName());
    Assert::assertCount(1, $company?->phones() ?? []);
    Assert::assertSame('988888888', $company?->phones()[0]->number());

    $tosAcceptance = $response->tosAcceptance();
    Assert::assertInstanceOf(AccountTosAcceptanceResponse::class, $tosAcceptance);
    Assert::assertSame('127.0.0.1', $tosAcceptance?->userIp());

    $token = $response->token();
    Assert::assertInstanceOf(AccountTokenResponse::class, $token);
    Assert::assertSame('access-123', $token?->accessToken());
    Assert::assertSame('refresh-123', $token?->refreshToken());
    Assert::assertSame('Bearer', $token?->tokenType());
    Assert::assertSame(3600, $token?->expiresIn());
    Assert::assertSame('accounts.read payments.create', $token?->scope());
});
