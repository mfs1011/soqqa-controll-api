<?php

namespace App\Shared\DTO;

class ItemResponseDTO extends BaseResponseDTO
{
    public function __construct(
        string $message,
        private readonly mixed $data
    ) {
        parent::__construct('success', $message);
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
