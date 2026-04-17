<?php

declare(strict_types=1);

use Dominasys\PagBank\Exceptions\ApiException;
use Dominasys\PagBank\Exceptions\RequestValidationException;
use Dominasys\PagBank\Support\Response;
use GuzzleHttp\Psr7\Response as PsrResponse;
use PHPUnit\Framework\Assert;

it('maps error messages payload to validation exception message', function (): void {
    $response = Response::fromPsrResponse(new PsrResponse(
        400,
        ['Content-Type' => 'application/json'],
        json_encode([
            'error_messages' => [
                [
                    'code' => '40002',
                    'description' => 'invalid_parameter',
                    'parameter_name' => 'charges[0].payment_method.pix',
                ],
            ],
        ], JSON_THROW_ON_ERROR),
    ));

    $exception = ApiException::fromResponse($response);

    Assert::assertInstanceOf(RequestValidationException::class, $exception);
    Assert::assertSame(
        '40002 invalid_parameter (charges[0].payment_method.pix)',
        $exception->getMessage(),
    );
});
