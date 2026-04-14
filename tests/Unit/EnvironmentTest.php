<?php

declare(strict_types=1);

use Dominasys\PagBank\Environment;
use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{
    public function testSandboxUrls(): void
    {
        self::assertSame('https://sandbox.api.pagseguro.com', Environment::Sandbox->apiBaseUri());
        self::assertSame('https://connect.sandbox.pagseguro.uol.com.br', Environment::Sandbox->connectBaseUri());
    }

    public function testProductionUrls(): void
    {
        self::assertSame('https://api.pagseguro.com', Environment::Production->apiBaseUri());
        self::assertSame('https://connect.pagseguro.uol.com.br', Environment::Production->connectBaseUri());
    }
}
