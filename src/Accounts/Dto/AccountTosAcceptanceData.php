<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Accounts\Dto;

final readonly class AccountTosAcceptanceData
{
    public function __construct(
        public ?string $userIp = null,
        public ?string $date = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->userIp !== null && $this->userIp !== '') {
            $payload['user_ip'] = $this->userIp;
        }

        if ($this->date !== null && $this->date !== '') {
            $payload['date'] = $this->date;
        }

        return $payload;
    }
}
