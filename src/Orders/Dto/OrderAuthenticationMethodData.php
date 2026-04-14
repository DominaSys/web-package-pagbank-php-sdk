<?php

declare(strict_types=1);

namespace Dominasys\PagBank\Orders\Dto;

final readonly class OrderAuthenticationMethodData
{
    public function __construct(
        public string $type,
        public ?string $id = null,
        public ?string $cavv = null,
        public ?string $eci = null,
        public ?string $xid = null,
        public ?string $version = null,
        public ?string $dstransId = null,
        public ?string $status = null,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $payload = ['type' => $this->type];

        foreach ([
            'id' => $this->id,
            'cavv' => $this->cavv,
            'eci' => $this->eci,
            'xid' => $this->xid,
            'version' => $this->version,
            'dstrans_id' => $this->dstransId,
            'status' => $this->status,
        ] as $key => $value) {
            if ($value !== null && $value !== '') {
                $payload[$key] = $value;
            }
        }

        return $payload;
    }
}
