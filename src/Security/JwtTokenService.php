<?php

namespace App\Security;

use App\Module\V1\User\DTO\TokensDTO;
use App\Module\V1\User\Entity\User;
use DateInterval;
use DateMalformedIntervalStringException;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

readonly class JwtTokenService
{
    public function __construct(
        private string $privateKeyPath,
        private string $publicKeyPath,
        private string $passphrase,
        private string $accessTokenPeriod,
        private string $refreshTokenPeriod,
    ) {}

    public function create(User $user): TokensDTO
    {
        return new TokensDTO(
            $this->generateAccessToken($user),
            $this->generateRefreshToken($user->getId())
        );
    }

    public function generateAccessToken(User $user): string
    {
        $privateKey = openssl_pkey_get_private(
            file_get_contents($this->privateKeyPath),
            $this->passphrase
        );

        $now = new DateTimeImmutable();
        $exp = $now->add(new DateInterval($this->accessTokenPeriod));

        return JWT::encode([
            'iat' => $now->getTimestamp(),
            'exp' => $exp->getTimestamp(),
            'id'  => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ], $privateKey, 'RS256');
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
    public function generateRefreshToken(int $userId): string
    {
        $privateKey = openssl_pkey_get_private(
            file_get_contents($this->privateKeyPath),
            $this->passphrase
        );

        $now = new DateTimeImmutable();
        $exp = $now->add(new DateInterval($this->refreshTokenPeriod));

        return JWT::encode([
            'iat' => $now->getTimestamp(),
            'exp' => $exp->getTimestamp(),
            'id'  => $userId,
        ], $privateKey, 'RS256');
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode(
            $token,
            new Key(file_get_contents($this->publicKeyPath), 'RS256')
        );
    }
}
