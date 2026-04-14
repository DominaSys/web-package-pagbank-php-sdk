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
use PHPUnit\Framework\TestCase;

final class AccountsValueObjectsTest extends TestCase
{
    public function testAccountCreateDtoConvertsToApiPayload(): void
    {
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

        self::assertSame([
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
    }

    public function testAccountResponseExposesTypedAccessors(): void
    {
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

        self::assertSame('ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36', $response->id());
        self::assertSame(AccountType::Seller, $response->type());
        self::assertSame('ACTIVE', $response->status());
        self::assertSame('2021-07-23T15:17:12.115601-03:00', $response->createdAt());
        self::assertSame('contato@domina.example', $response->email());
        self::assertSame('RESTAURANT', $response->businessCategory());
        self::assertTrue($response->accountAdvanced());
        self::assertSame(200, $response->statusCode());

        $person = $response->person();
        self::assertInstanceOf(AccountPersonResponse::class, $person);
        self::assertSame('José Carlos Silva', $person?->name());
        self::assertSame('1991-10-10', $person?->birthDate());
        self::assertSame('Maria Silva', $person?->motherName());
        self::assertSame('12345678900', $person?->taxId());
        self::assertInstanceOf(AccountAddressResponse::class, $person?->address());
        self::assertSame('São Paulo', $person?->address()?->city());
        self::assertCount(1, $person?->phones() ?? []);
        self::assertInstanceOf(AccountPhoneResponse::class, $person?->phones()[0] ?? null);
        self::assertSame(AccountPhoneType::Mobile, $person?->phones()[0]->type());

        $company = $response->company();
        self::assertInstanceOf(AccountCompanyResponse::class, $company);
        self::assertSame('DominaSys LTDA', $company?->name());
        self::assertSame('DominaSys', $company?->tradeName());
        self::assertCount(1, $company?->phones() ?? []);

        $tosAcceptance = $response->tosAcceptance();
        self::assertInstanceOf(AccountTosAcceptanceResponse::class, $tosAcceptance);
        self::assertSame('127.0.0.1', $tosAcceptance?->userIp());

        $token = $response->token();
        self::assertInstanceOf(AccountTokenResponse::class, $token);
        self::assertSame('access-123', $token?->accessToken());
        self::assertSame('refresh-123', $token?->refreshToken());
        self::assertSame('Bearer', $token?->tokenType());
        self::assertSame(3600, $token?->expiresIn());
        self::assertSame('accounts.read payments.create', $token?->scope());
    }
}
