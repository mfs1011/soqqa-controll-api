<?php

namespace App\Module\V1\User\UseCase;

use App\Module\V1\User\DTO\TokensDTO;
use App\Module\V1\User\Repository\UserRepository;
use App\Shared\Security\Token\TokenServiceInterface;
use DateMalformedIntervalStringException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

final readonly class AuthenticateUser
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordEncoder,
        private TokenServiceInterface $tokenGenerator
    ) {
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
    public function execute(string $email, string $password): TokensDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid credentials');
        }

        return $this->tokenGenerator->create($user);
    }
}
