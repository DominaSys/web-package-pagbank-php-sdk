<?php

declare(strict_types=1);

use Dominasys\PagBank\Connect\Dto\AuthorizationUrlData;
use Dominasys\PagBank\Connect\Dto\AuthorizationScopes;
use Dominasys\PagBank\Connect\Dto\CreateApplicationData;
use Dominasys\PagBank\Connect\Response\ApplicationResponse;
use Dominasys\PagBank\Connect\Response\TokenResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\Assert;

it('create application data converts to api payload', function (): void {
    $data = new CreateApplicationData(
        name: 'DominaPay',
        description: 'Plataforma de pagamentos',
        site: 'https://domina.example',
        redirectUri: 'https://domina.example/callback',
        logo: 'https://domina.example/logo.png',
    );

    Assert::assertSame([
        'name' => 'DominaPay',
        'description' => 'Plataforma de pagamentos',
        'site' => 'https://domina.example',
        'redirect_uri' => 'https://domina.example/callback',
        'logo' => 'https://domina.example/logo.png',
    ], $data->toArray());
});

it('application response exposes typed accessors', function (): void {
    $response = ApplicationResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
        201,
        ['Content-Type' => 'application/json'],
        json_encode([
            'id' => 'app-123',
            'client_id' => 'client-123',
        ], JSON_THROW_ON_ERROR),
    )));

    Assert::assertSame('app-123', $response->applicationId());
    Assert::assertSame('client-123', $response->clientId());
    Assert::assertSame(201, $response->statusCode());
});

it('authorization url data builds query payload', function (): void {
    $data = new AuthorizationUrlData(
        clientId: 'client-123',
        redirectUri: 'https://domina.example/callback',
        scopes: new AuthorizationScopes('payments.read', 'payments.create'),
        state: 'tenant-123',
    );

    Assert::assertSame([
        'client_id' => 'client-123',
        'response_type' => 'code',
        'redirect_uri' => 'https://domina.example/callback',
        'scope' => 'payments.read payments.create',
        'state' => 'tenant-123',
    ], $data->toQuery());
});

it('token response exposes typed accessors', function (): void {
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

    Assert::assertSame('access-123', $response->accessToken());
    Assert::assertSame('refresh-123', $response->refreshToken());
    Assert::assertSame('Bearer', $response->tokenType());
    Assert::assertSame(3600, $response->expiresIn());
});
