<?php

namespace App\Shared\DTO;

class ErrorResponseDTO extends BaseResponseDTO
{
    public function __construct(
        string $message,
        private readonly array $errors = []
    )
    {
        parent::__construct('error', $message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
