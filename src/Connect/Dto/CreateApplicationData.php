<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Connect\Dto;

final readonly class CreateApplicationData
{
    public function __construct(
        public string $name,
        public string $description,
        public string $site,
        public string $redirectUri,
        public ?string $logo = null,
    ) {
    }

    /**
     * @return array{
     *     name: string,
     *     description: string,
     *     site: string,
     *     redirect_uri: string,
     *     logo?: ?string
     * }
     */
    public function toArray(): array
    {
        $payload = [
            'name' => $this->name,
            'description' => $this->description,
            'site' => $this->site,
            'redirect_uri' => $this->redirectUri,
            'logo' => $this->logo,
        ];

        return array_filter($payload, static fn (mixed $value): bool => $value !== null && $value !== '');
    }
}
