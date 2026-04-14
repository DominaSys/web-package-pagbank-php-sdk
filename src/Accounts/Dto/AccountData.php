<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Dto;

use Dominasys\PagBank\Accounts\Enums\AccountType;
use InvalidArgumentException;

final readonly class AccountData
{
    public function __construct(
        public AccountType $type,
        public string $email,
        public AccountPersonData $person,
        public AccountTosAcceptanceData $tosAcceptance,
        public ?string $businessCategory = null,
        public ?AccountCompanyData $company = null,
    ) {
        if ($this->email === '') {
            throw new InvalidArgumentException('The account email cannot be empty.');
        }

        if (($this->type === AccountType::Seller || $this->type === AccountType::Enterprise) && $this->company === null) {
            throw new InvalidArgumentException('The company data is required for SELLER and ENTERPRISE accounts.');
        }

        if (($this->type === AccountType::Seller || $this->type === AccountType::Enterprise) && ($this->businessCategory === null || $this->businessCategory === '')) {
            throw new InvalidArgumentException('The business category is required for SELLER and ENTERPRISE accounts.');
        }
    }

    public static function buyer(
        string $email,
        AccountPersonData $person,
        AccountTosAcceptanceData $tosAcceptance,
    ): self {
        return new self(
            type: AccountType::Buyer,
            email: $email,
            person: $person,
            tosAcceptance: $tosAcceptance,
        );
    }

    public static function seller(
        string $email,
        string $businessCategory,
        AccountPersonData $person,
        AccountCompanyData $company,
        AccountTosAcceptanceData $tosAcceptance,
    ): self {
        return new self(
            type: AccountType::Seller,
            email: $email,
            person: $person,
            tosAcceptance: $tosAcceptance,
            businessCategory: $businessCategory,
            company: $company,
        );
    }

    public static function enterprise(
        string $email,
        string $businessCategory,
        AccountPersonData $person,
        AccountCompanyData $company,
        AccountTosAcceptanceData $tosAcceptance,
    ): self {
        return new self(
            type: AccountType::Enterprise,
            email: $email,
            person: $person,
            tosAcceptance: $tosAcceptance,
            businessCategory: $businessCategory,
            company: $company,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'type' => $this->type->value,
            'email' => $this->email,
            'person' => $this->person->toArray(),
            'tos_acceptance' => $this->tosAcceptance->toArray(),
        ];

        if ($this->businessCategory !== null && $this->businessCategory !== '') {
            $payload['business_category'] = $this->businessCategory;
        }

        if ($this->company instanceof AccountCompanyData) {
            $payload['company'] = $this->company->toArray();
        }

        return $payload;
    }
}
