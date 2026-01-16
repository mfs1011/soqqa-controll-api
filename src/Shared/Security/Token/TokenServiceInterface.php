<?php

namespace App\Shared\Security\Token;

use App\Module\V1\User\DTO\TokensDTO;
use App\Module\V1\User\Entity\User;

interface TokenServiceInterface
{
    public function create(User $user): TokensDTO;
    public function generateAccessToken(User $user): string;
    public function generateRefreshToken(int $userId): string;
    public function decode(string $token): array;
}
