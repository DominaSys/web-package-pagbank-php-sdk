<?php

declare(strict_types=1);

use Dominasys\PagBank\Environment;
use PHPUnit\Framework\Assert;

it('sandbox urls', function (): void {
    Assert::assertSame('https://sandbox.api.pagseguro.com', Environment::Sandbox->apiBaseUri());
    Assert::assertSame('https://connect.sandbox.pagseguro.uol.com.br', Environment::Sandbox->connectBaseUri());
});

it('production urls', function (): void {
    Assert::assertSame('https://api.pagseguro.com', Environment::Production->apiBaseUri());
    Assert::assertSame('https://connect.pagseguro.uol.com.br', Environment::Production->connectBaseUri());
});
