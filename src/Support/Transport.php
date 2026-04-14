<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

use Dominasys\PagBank\Exceptions\InvalidConfigurationException;

final readonly class Transport
{
    public function __construct(
        public float $timeout = 30.0,
        public float $connectTimeout = 10.0,
    ) {
        if ($this->timeout <= 0) {
            throw InvalidConfigurationException::invalidTimeout('timeout');
        }

        if ($this->connectTimeout <= 0) {
            throw InvalidConfigurationException::invalidTimeout('connectTimeout');
        }
    }
}
