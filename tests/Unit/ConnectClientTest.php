<?php

declare(strict_types=1);

use Dominasys\PagBank\Environment;
use Dominasys\PagBank\Exceptions\AuthenticationException;
use Dominasys\PagBank\Exceptions\RequestValidationException;
use Dominasys\PagBank\Connect\Dto\AuthorizationUrlData;
use Dominasys\PagBank\Connect\Dto\AuthorizationScopes;
use Dominasys\PagBank\Connect\Dto\CreateApplicationData;
use Dominasys\PagBank\Support\Credentials;
use Dominasys\PagBank\Support\Endpoints;
use Dominasys\PagBank\PagBank;
use Dominasys\PagBank\Support\Transport;
use Dominasys\PagBank\Support\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ConnectClientTest extends TestCase
{
    public function testBuildsAuthorizationUrl(): void
    {
        $sdk = PagBank::make(Configuration::make(
            endpoints: new Endpoints(environment: Environment::Sandbox),
        ));

        $url = $sdk->connect()->authorizationUrl(new AuthorizationUrlData(
            clientId: 'client-123',
            redirectUri: 'https://domina.example/callback',
            scopes: new AuthorizationScopes('payments.read', 'payments.create'),
            state: 'tenant-123',
        ));

        self::assertSame(
            'https://connect.sandbox.pagseguro.uol.com.br/oauth2/authorize?client_id=client-123&response_type=code&redirect_uri=https%3A%2F%2Fdomina.example%2Fcallback&scope=payments.read%20payments.create&state=tenant-123',
            $url,
        );
    }

    public function testSendsCreateApplicationWithBearerToken(): void
    {
        $history = [];

        $sdk = $this->makeSdkWithHistory(
            history: $history,
            configuration: Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
                transport: new Transport(),
            ),
            response: new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'client_id' => 'client-123',
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->connect()->createApplication(new CreateApplicationData(
            name: 'DominaPay',
            description: 'Plataforma de pagamentos',
            site: 'https://domina.example',
            redirectUri: 'https://domina.example/callback',
            logo: 'https://domina.example/logo.png',
        ));

        self::assertSame('client-123', $response->clientId());
        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('https://sandbox.api.pagseguro.com/oauth2/application', (string) $history[0]['request']->getUri());
    }

    public function testSendsTokenExchangeWithConnectCredentials(): void
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
            response: new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'access_token' => 'access-123',
                'refresh_token' => 'refresh-123',
            ], JSON_THROW_ON_ERROR)),
        );

        $response = $sdk->connect()->exchangeAuthorizationCode('auth-code', 'https://domina.example/callback');

        self::assertSame('access-123', $response->accessToken());
        self::assertSame('refresh-123', $response->refreshToken());
        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame('Bearer bearer-token', $history[0]['request']->getHeaderLine('Authorization'));
        self::assertSame('client-id', $history[0]['request']->getHeaderLine('X_CLIENT_ID'));
        self::assertSame('client-secret', $history[0]['request']->getHeaderLine('X_CLIENT_SECRET'));
        self::assertSame('https://sandbox.api.pagseguro.com/oauth2/token', (string) $history[0]['request']->getUri());
    }

    public function testMapsApiValidationErrorsToDomainException(): void
    {
        $sdk = PagBank::make(
            Configuration::make(
                endpoints: new Endpoints(environment: Environment::Sandbox),
                credentials: new Credentials(bearerToken: 'bearer-token'),
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
        $sdk->connect()->createApplication(new CreateApplicationData(
            name: 'DominaPay',
            description: 'Plataforma de pagamentos',
            site: 'https://domina.example',
            redirectUri: 'https://domina.example/callback',
        ));
    }

    public function testMapsAuthenticationErrorsToDomainException(): void
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
                    new Response(401, ['Content-Type' => 'application/json'], json_encode([
                        'error' => 'invalid_token',
                    ], JSON_THROW_ON_ERROR)),
                ])),
            ]),
        );

        $this->expectException(AuthenticationException::class);
        $sdk->connect()->refreshAccessToken('refresh-token');
    }

    /**
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
