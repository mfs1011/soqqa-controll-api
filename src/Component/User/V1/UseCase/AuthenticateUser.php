<?php

namespace App\Component\User\V1\UseCase;

use App\Component\User\V1\DTO\TokensDTO;
use App\Component\User\V1\Repository\UserRepository;
use App\Security\JwtTokenService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

readonly class AuthenticateUser
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordEncoder,
        private JwtTokenService $tokenGenerator
    ) {
    }

    public function execute(string $email, string $password): TokensDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid credentials');
        }

        return $this->tokenGenerator->create($user);
    }
}
