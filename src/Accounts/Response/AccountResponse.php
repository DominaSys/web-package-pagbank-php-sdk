<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Response;

use Dominasys\PagBank\Accounts\Enums\AccountType;
use Dominasys\PagBank\Support\Response;

final class AccountResponse extends AccountResponseNode
{
    public function __construct(
        private readonly int $statusCode,
        array $payload,
    ) {
        parent::__construct($payload);
    }

    public static function fromResponse(Response $response): self
    {
        return new self($response->statusCode(), $response->toArray());
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function id(): ?string
    {
        return $this->stringValue('id');
    }

    public function type(): ?AccountType
    {
        $value = $this->stringValue('type');

        return $value !== null ? AccountType::tryFrom($value) : null;
    }

    public function status(): ?string
    {
        return $this->stringValue('status');
    }

    public function createdAt(): ?string
    {
        return $this->stringValue('created_at');
    }

    public function email(): ?string
    {
        return $this->stringValue('email');
    }

    public function businessCategory(): ?string
    {
        return $this->stringValue('business_category');
    }

    public function accountAdvanced(): ?bool
    {
        return $this->boolValue('account_advanced');
    }

    public function person(): ?AccountPersonResponse
    {
        $payload = $this->nestedPayload('person');

        return $payload !== null ? AccountPersonResponse::fromArray($payload) : null;
    }

    public function company(): ?AccountCompanyResponse
    {
        $payload = $this->nestedPayload('company');

        return $payload !== null ? AccountCompanyResponse::fromArray($payload) : null;
    }

    public function tosAcceptance(): ?AccountTosAcceptanceResponse
    {
        $payload = $this->nestedPayload('tos_acceptance');

        return $payload !== null ? AccountTosAcceptanceResponse::fromArray($payload) : null;
    }

    public function token(): ?AccountTokenResponse
    {
        $payload = $this->nestedPayload('token');

        return $payload !== null ? AccountTokenResponse::fromArray($payload) : null;
    }
}
