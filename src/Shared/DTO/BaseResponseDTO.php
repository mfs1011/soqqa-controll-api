<?php

namespace App\Shared\DTO;

class BaseResponseDTO
{
    public function __construct(
        private readonly string $status,
        private readonly string $message
    ) {
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
