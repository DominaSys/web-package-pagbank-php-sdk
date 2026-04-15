<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Support;

abstract class ResponseNode
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        protected readonly array $payload,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected static function fromArrayPayload(array $payload): static
    {
        return new static($payload);
    }

    protected function stringValue(string $key): ?string
    {
        $value = $this->payload[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    protected function intValue(string $key): ?int
    {
        $value = $this->payload[$key] ?? null;

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }

    protected function boolValue(string $key): ?bool
    {
        $value = $this->payload[$key] ?? null;

        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value === 1;
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function nestedPayload(string $key): ?array
    {
        $value = $this->payload[$key] ?? null;

        return is_array($value) && $value !== [] ? $value : null;
    }
}
