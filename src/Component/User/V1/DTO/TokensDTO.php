<?php

declare(strict_types=1);

namespace App\Component\User\V1\DTO;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class TokensDTO
{
    public function __construct(
        #[Groups(['users:read'])]
        private string $accessToken,

        #[Groups(['users:read'])]
        private string $refreshToken
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
