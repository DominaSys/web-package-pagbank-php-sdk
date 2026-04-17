<?php

declare(strict_types=1);

use Dominasys\PagBank\PublicKeys\Response\PublicKeyResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\Assert;

it('public key response parses json payload', function (): void {
    $response = PublicKeyResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
        200,
        ['Content-Type' => 'application/json'],
        json_encode([
            'id' => 'PUBK_123',
            'type' => 'card',
            'public_key' => 'PUBLIC-KEY-123',
            'created_at' => '2026-04-14T12:00:00Z',
            'updated_at' => '2026-04-14T12:10:00Z',
        ], JSON_THROW_ON_ERROR),
    )));

    Assert::assertSame('PUBK_123', $response->id());
    Assert::assertSame('card', $response->type());
    Assert::assertSame('PUBLIC-KEY-123', $response->publicKey());
    Assert::assertSame('2026-04-14T12:00:00Z', $response->createdAt());
    Assert::assertSame('2026-04-14T12:10:00Z', $response->updatedAt());
});

it('public key response falls back to raw body', function (): void {
    $response = PublicKeyResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
        201,
        ['Content-Type' => 'text/plain'],
        'PUBLIC-KEY-RAW',
    )));

    Assert::assertSame('PUBLIC-KEY-RAW', $response->publicKey());
    Assert::assertNull($response->createdAt());
    Assert::assertNull($response->updatedAt());
});
