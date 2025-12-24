<?php

namespace App\Component\User\Controller;

use App\Component\User\DTO\RefreshTokenDTO;
use App\Component\User\DTO\RefreshTokenRequestDTO;
use App\Component\User\Exception\AuthException;
use App\Component\User\Repository\UserRepository;
use App\Security\JwtTokenService;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UsersAuthByRefreshTokenAction extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/refresh-auth', methods: ["POST"])]
    public function refreshAuth(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] RefreshTokenRequestDTO $requestDto,
        UserRepository $userRepository,
        JwtTokenService $tokenGenerator,
        DenormalizerInterface $denormalizer
    ): Response
    {
        $refreshTokenDto = $this->arrayToDto($denormalizer, $tokenGenerator->decode($requestDto->getRefreshToken()));

        $user = $userRepository->find($refreshTokenDto->getId());

        if ($user === null) {
            throw new AuthException('Invalid credentials');
        }

        return $this->success($tokenGenerator->create($user));
    }

    /**
     * @throws ExceptionInterface
     */
    private function arrayToDto(DenormalizerInterface $denormalizer, array $data): RefreshTokenDto
    {
        return $denormalizer->denormalize($data, RefreshTokenDto::class);
    }
}
