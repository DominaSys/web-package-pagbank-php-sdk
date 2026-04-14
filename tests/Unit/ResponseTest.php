<?php

declare(strict_types=1);

use Dominasys\PagBank\Support\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testParsesJsonResponse(): void
    {
        $response = Response::fromPsrResponse(new PsrResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'id' => 'app-123',
                'name' => 'DominaPay',
            ], JSON_THROW_ON_ERROR),
        ));

        self::assertSame(200, $response->statusCode());
        self::assertSame('app-123', $response->json()['id']);
        self::assertSame('DominaPay', $response->toArray()['name']);
    }

    public function testPreservesRawBodyWhenResponseIsNotJson(): void
    {
        $response = Response::fromPsrResponse(new PsrResponse(
            500,
            ['Content-Type' => 'text/plain'],
            'service unavailable',
        ));

        self::assertSame(500, $response->statusCode());
        self::assertSame([], $response->json());
        self::assertSame('service unavailable', $response->body());
    }
}
