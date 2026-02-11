<?php

namespace App\Module\V1\User\UseCase;

use App\Module\V1\User\DTO\RefreshTokenDTO;
use App\Module\V1\User\DTO\TokensDTO;
use App\Module\V1\User\Exception\AuthException;
use App\Module\V1\User\Repository\UserRepository;
use App\Shared\Security\Token\TokenServiceInterface;
use DateInterval;
use DateMalformedIntervalStringException;
use DateTimeImmutable;

final readonly class RefreshAuthenticateUser
{
    public function __construct(
        private TokenServiceInterface $tokenService,
        private UserRepository  $userRepository,
        private string $refreshPeriod
    ) {
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
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

        $issuedAt = (new DateTimeImmutable())->setTimestamp($payload['iat']);
        $expiresAt = $issuedAt->add(new DateInterval($this->refreshPeriod));

        if ($expiresAt < new DateTimeImmutable()) {
            throw new AuthException('Refresh token expired');
        }

        $user = $this->userRepository->find($payload['id']);

        if (!$user) {
            throw new AuthException('Invalid credentials');
        }

        return $this->tokenService->create($user);
    }
}
