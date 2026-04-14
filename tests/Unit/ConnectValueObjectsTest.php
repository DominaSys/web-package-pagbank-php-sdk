<?php

declare(strict_types=1);

use Dominasys\PagBank\Connect\Dto\CreateApplicationData;
use Dominasys\PagBank\Connect\Dto\AuthorizationUrlData;
use Dominasys\PagBank\Connect\Dto\AuthorizationScopes;
use Dominasys\PagBank\Connect\Response\ApplicationResponse;
use Dominasys\PagBank\Connect\Response\TokenResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

final class ConnectValueObjectsTest extends TestCase
{
    public function testCreateApplicationDataConvertsToApiPayload(): void
    {
        $data = new CreateApplicationData(
            name: 'DominaPay',
            description: 'Plataforma de pagamentos',
            site: 'https://domina.example',
            redirectUri: 'https://domina.example/callback',
            logo: 'https://domina.example/logo.png',
        );

        self::assertSame([
            'name' => 'DominaPay',
            'description' => 'Plataforma de pagamentos',
            'site' => 'https://domina.example',
            'redirect_uri' => 'https://domina.example/callback',
            'logo' => 'https://domina.example/logo.png',
        ], $data->toArray());
    }

    public function testApplicationResponseExposesTypedAccessors(): void
    {
        $response = ApplicationResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
            201,
            ['Content-Type' => 'application/json'],
            json_encode([
                'id' => 'app-123',
                'client_id' => 'client-123',
            ], JSON_THROW_ON_ERROR),
        )));

        self::assertSame('app-123', $response->applicationId());
        self::assertSame('client-123', $response->clientId());
        self::assertSame(201, $response->statusCode());
    }

    public function testAuthorizationUrlDataBuildsQueryPayload(): void
    {
        $data = new AuthorizationUrlData(
            clientId: 'client-123',
            redirectUri: 'https://domina.example/callback',
            scopes: new AuthorizationScopes('payments.read', 'payments.create'),
            state: 'tenant-123',
        );

        self::assertSame([
            'client_id' => 'client-123',
            'response_type' => 'code',
            'redirect_uri' => 'https://domina.example/callback',
            'scope' => 'payments.read payments.create',
            'state' => 'tenant-123',
        ], $data->toQuery());
    }

    public function testTokenResponseExposesTypedAccessors(): void
    {
        $response = TokenResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'access_token' => 'access-123',
                'refresh_token' => 'refresh-123',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ], JSON_THROW_ON_ERROR),
        )));

        self::assertSame('access-123', $response->accessToken());
        self::assertSame('refresh-123', $response->refreshToken());
        self::assertSame('Bearer', $response->tokenType());
        self::assertSame(3600, $response->expiresIn());
    }
}
