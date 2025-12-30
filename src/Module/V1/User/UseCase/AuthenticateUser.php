<?php

namespace App\Module\V1\User\UseCase;

use App\Module\V1\User\DTO\TokensDTO;
use App\Module\V1\User\Exception\AuthException;
use App\Module\V1\User\Repository\UserRepository;
use App\Security\JwtTokenService;
use DateMalformedIntervalStringException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class AuthenticateUser
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordEncoder,
        private JwtTokenService $tokenGenerator
    ) {
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
    public function execute(string $email, string $password): TokensDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new AuthException('Invalid credentials');
        }

        return $this->tokenGenerator->create($user);
    }
}
