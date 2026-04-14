<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Response;

final class AccountPersonResponse extends AccountResponseNode
{
    public static function fromArray(array $payload): self
    {
        return self::fromArrayPayload($payload);
    }

    public function name(): ?string
    {
        return $this->stringValue('name');
    }

    public function birthDate(): ?string
    {
        return $this->stringValue('birth_date');
    }

    public function motherName(): ?string
    {
        return $this->stringValue('mother_name');
    }

    public function taxId(): ?string
    {
        return $this->stringValue('tax_id');
    }

    public function address(): ?AccountAddressResponse
    {
        $payload = $this->nestedPayload('address');

        return $payload !== null ? AccountAddressResponse::fromArray($payload) : null;
    }

    /**
     * @return array<int, AccountPhoneResponse>
     */
    public function phones(): array
    {
        return array_values(array_map(
            static fn (array $payload): AccountPhoneResponse => AccountPhoneResponse::fromArray($payload),
            array_filter(
                $this->listPayload('phones'),
                static fn (mixed $payload): bool => is_array($payload),
            ),
        ));
    }
}
