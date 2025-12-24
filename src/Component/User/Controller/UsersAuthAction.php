<?php

namespace App\Component\User\Controller;

use App\Component\User\DTO\UserAuthDTO;
use App\Component\User\Exception\AuthException;
use App\Component\User\Repository\UserRepository;
use App\Security\JwtTokenService;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UsersAuthAction extends AbstractController
{
    #[Route('/auth', methods: ["POST"])]
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] UserAuthDTO $authData,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordEncoder,
        JwtTokenService $tokenGenerator
    ): Response
    {
        $foundUser = $userRepository->findOneBy(['email' => $authData->getEmail()]);

        if ($foundUser === null) {
            throw new AuthException('Invalid credentials');
        }

        if (!$passwordEncoder->isPasswordValid($foundUser, $authData->getPassword())) {
            throw new AuthException('Invalid credentials');
        }

        return $this->success($tokenGenerator->create($foundUser));
    }
}
