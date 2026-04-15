<?php

declare(strict_types=1);

use Dominasys\PagBank\PublicKeys\Response\PublicKeyResponse;
use Dominasys\PagBank\Support\Response as PagBankResponse;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

final class PublicKeysValueObjectsTest extends TestCase
{
    public function testPublicKeyResponseParsesJsonPayload(): void
    {
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

        self::assertSame('PUBK_123', $response->id());
        self::assertSame('card', $response->type());
        self::assertSame('PUBLIC-KEY-123', $response->publicKey());
        self::assertSame('2026-04-14T12:00:00Z', $response->createdAt());
        self::assertSame('2026-04-14T12:10:00Z', $response->updatedAt());
    }

    public function testPublicKeyResponseFallsBackToRawBody(): void
    {
        $response = PublicKeyResponse::fromResponse(PagBankResponse::fromPsrResponse(new PsrResponse(
            201,
            ['Content-Type' => 'text/plain'],
            'PUBLIC-KEY-RAW',
        )));

        self::assertSame('PUBLIC-KEY-RAW', $response->publicKey());
        self::assertNull($response->createdAt());
        self::assertNull($response->updatedAt());
    }
}
