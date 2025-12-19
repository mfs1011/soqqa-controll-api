<?php

namespace App\Component\DTO;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class RefreshTokenRequestDTO
{
    public function __construct(
        #[Groups(['user:write'])]
        private string $refreshToken
    ) {
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
