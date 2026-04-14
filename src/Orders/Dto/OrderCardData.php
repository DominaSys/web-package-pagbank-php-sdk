<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderCardData
{
    public function __construct(
        public ?string $id = null,
        public ?string $encrypted = null,
        public ?string $number = null,
        public ?string $networkToken = null,
        public ?int $expMonth = null,
        public ?int $expYear = null,
        public ?string $securityCode = null,
        public ?bool $store = null,
        public ?OrderCardHolderData $holder = null,
        public ?OrderWalletData $wallet = null,
        public ?OrderTokenData $tokenData = null,
        public ?OrderAuthenticationMethodData $authenticationMethod = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

        foreach ([
            'id' => $this->id,
            'encrypted' => $this->encrypted,
            'number' => $this->number,
            'network_token' => $this->networkToken,
            'exp_month' => $this->expMonth,
            'exp_year' => $this->expYear,
            'security_code' => $this->securityCode,
            'store' => $this->store,
        ] as $key => $value) {
            if ($value !== null && $value !== '') {
                $payload[$key] = $value;
            }
        }

        if ($this->holder instanceof OrderCardHolderData) {
            $payload['holder'] = $this->holder->toArray();
        }

        if ($this->wallet instanceof OrderWalletData) {
            $payload['wallet'] = $this->wallet->toArray();
        }

        if ($this->tokenData instanceof OrderTokenData) {
            $payload['token_data'] = $this->tokenData->toArray();
        }

        if ($this->authenticationMethod instanceof OrderAuthenticationMethodData) {
            $payload['authentication_method'] = $this->authenticationMethod->toArray();
        }

        return $payload;
    }
}
