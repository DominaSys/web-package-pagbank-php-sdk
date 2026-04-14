<?php

declare(strict_types=1);

use Dominasys\PagBank\Accounts\Dto\AccountAddressData;
use Dominasys\PagBank\Accounts\Dto\AccountCompanyData;
use Dominasys\PagBank\Accounts\Dto\AccountData;
use Dominasys\PagBank\Accounts\Dto\AccountPersonData;
use Dominasys\PagBank\Accounts\Dto\AccountPhoneData;
use Dominasys\PagBank\Accounts\Dto\AccountTosAcceptanceData;
use Dominasys\PagBank\Accounts\Enums\AccountPhoneType;
use Dominasys\PagBank\Environment;
use Dominasys\PagBank\Exceptions\RequestValidationException;
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

final class AccountsClientTest extends TestCase
{
    public function testCreatesAccountWithConnectCredentials(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(
                    bearerToken: 'bearer-token',
                    clientId: 'client-id',
                    clientSecret: 'client-secret',
                ),
                transport: new Transport(),
            ),
            response: new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'id' => 'ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36',
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->accounts()->createAccount(AccountData::seller(
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
                    new AccountPhoneData('55', '11', '999999999', AccountPhoneType::Mobile),
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
        ));

        self::assertSame('ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36', $response->id());
        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('client-id', $history[0]['request']->getHeaderLine('x-client-id'));
        self::assertSame('client-secret', $history[0]['request']->getHeaderLine('x-client-secret'));
        self::assertSame('https://sandbox.api.pagseguro.com/accounts', (string) $history[0]['request']->getUri());

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
        ], json_decode((string) $history[0]['request']->getBody(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function testReadsAccountWithClientToken(): void
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
                'id' => 'ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36',
                'type' => 'BUYER',
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->accounts()->getAccount('ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36', 'client-token-123');

        self::assertSame('ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36', $response->id());
        self::assertCount(1, $history);
        self::assertSame('GET', $history[0]['request']->getMethod());
        self::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('client-token-123', $history[0]['request']->getHeaderLine('x-client-token'));
        self::assertSame('https://sandbox.api.pagseguro.com/accounts/ACCO_F74B8490-77C0-4F9C-88EA-E98735B4CB36', (string) $history[0]['request']->getUri());
    }

    public function testMapsAccountApiValidationErrorsToDomainException(): void
    {
        $sdk = PagBank::make(
            Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(
                    bearerToken: 'bearer-token',
                    clientId: 'client-id',
                    clientSecret: 'client-secret',
                ),
                transport: new Transport(),
            ),
            new Client([
                'handler' => HandlerStack::create(new MockHandler([
                    new Response(400, ['Content-Type' => 'application/json'], json_encode([
                        'error' => 'invalid_request',
                    ], JSON_THROW_ON_ERROR)),
                ])),
            ]),
        );

        $this->expectException(RequestValidationException::class);

        $sdk->accounts()->createAccount(AccountData::buyer(
            email: 'buyer@domina.example',
            person: new AccountPersonData(
                name: 'José Carlos Silva',
                birthDate: '1991-10-10',
                motherName: 'Maria Silva',
                taxId: '12345678900',
            ),
            tosAcceptance: new AccountTosAcceptanceData(
                userIp: '127.0.0.1',
                date: '2024-01-01T10:00:00-03:00',
            ),
        ));
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
