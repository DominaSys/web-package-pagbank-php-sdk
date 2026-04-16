<?php

declare(strict_types=1);

use Dominasys\PagBank\Support\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\Assert;

test('parses json response', function (): void {
    $response = Response::fromPsrResponse(new PsrResponse(
        200,
        ['Content-Type' => 'application/json'],
        json_encode([
            'id' => 'app-123',
            'name' => 'DominaPay',
        ], JSON_THROW_ON_ERROR),
    ));

    Assert::assertSame(200, $response->statusCode());
    Assert::assertSame('app-123', $response->json()['id']);
    Assert::assertSame('DominaPay', $response->toArray()['name']);
});

test('preserves raw body when response is not json', function (): void {
    $response = Response::fromPsrResponse(new PsrResponse(
        500,
        ['Content-Type' => 'text/plain'],
        'service unavailable',
    ));

    Assert::assertSame(500, $response->statusCode());
    Assert::assertSame([], $response->json());
    Assert::assertSame('service unavailable', $response->body());
});
