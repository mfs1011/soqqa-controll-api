<?php

namespace App\Component\User\V1\UseCase;

use App\Component\User\V1\DTO\RefreshTokenDTO;
use App\Component\User\V1\DTO\TokensDTO;
use App\Component\User\V1\Exception\AuthException;
use App\Component\User\V1\Repository\UserRepository;
use App\Security\JwtTokenService;

final readonly class RefreshAuthenticateUser
{
    public function __construct(
        private JwtTokenService $tokenService,
        private UserRepository  $users
    ) {
    }

    public function execute(string $refreshToken): TokensDTO
    {
        $payload = $this->tokenService->decode($refreshToken);

        if (
            !isset($payload['id'], $payload['iat']) ||
            !is_int($payload['id']) ||
            !is_int($payload['iat'])
        ) {
            throw new AuthException('Invalid refresh token');
        }

        $refreshTokenDto = new RefreshTokenDTO(
            id: $payload['id'],
            iat: $payload['iat']
        );

        $user = $this->users->find($refreshTokenDto->getId());

        if (!$user) {
            throw new AuthException('Invalid credentials');
        }

        return $this->tokenService->create($user);
    }
}
