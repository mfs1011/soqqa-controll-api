<?php

namespace App\Shared\DTO;

class CollectionResponseDTO extends BaseResponseDTO
{
    public function __construct(
        string $message,
        private readonly array $data,
        private readonly ?object $meta = null
    ) {
        parent::__construct('success', $message);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMeta(): ?object
    {
        return $this->meta;
    }
}

