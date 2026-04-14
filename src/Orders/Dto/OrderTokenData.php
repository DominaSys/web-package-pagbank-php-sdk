<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderTokenData
{
    public function __construct(
        public string $requestorId,
        public ?string $wallet = null,
        public ?string $cryptogram = null,
        public ?string $ecommerceDomain = null,
        public ?int $assuranceLevel = null,
    ) {
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        $payload = ['requestor_id' => $this->requestorId];

        foreach ([
            'wallet' => $this->wallet,
            'cryptogram' => $this->cryptogram,
            'ecommerce_domain' => $this->ecommerceDomain,
            'assurance_level' => $this->assuranceLevel,
        ] as $key => $value) {
            if ($value !== null && $value !== '') {
                $payload[$key] = $value;
            }
        }

        return $payload;
    }
}
