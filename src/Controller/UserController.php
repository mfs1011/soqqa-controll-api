<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\User\DTO\RefreshTokenDTO;
use App\Component\User\DTO\RefreshTokenRequestDTO;
use App\Component\User\DTO\UserAuthDTO;
use App\Component\User\DTO\UserCreateDTO;
use App\Component\User\DTO\UserDTO;
use App\Component\User\Exception\AuthException;
use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Repository\UserRepository;
use App\Security\JwtTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Route('/api/users', name: 'users')]
final class UserController extends AbstractController
{
    #[Route('', methods: ["GET"])]
    public function index(UserRepository $repository, Request $request): JsonResponse
    {
        $queries = $request->query->all();
        $data = $repository->findAllWithPagination($queries);

        return $this->success($data, context: ['groups' => ['user:read']]);
    }

    #[Route('', methods: ["POST"])]
    public function store(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] UserCreateDTO $dto,
        UserFactory      $factory,
        UserManager      $manager,
    ): Response
    {
        $user = $factory->create($dto->getEmail(), $dto->getPassword());
        $manager->save($user, true);

        return $this->success($user, 'User successfully created.', Response::HTTP_CREATED, context: ['groups' => ['user:read']]);
    }

    #[Route('/auth', methods: ["POST"])]
    public function auth(
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

    /**
     * @throws ExceptionInterface
     */
    #[Route('/refresh-auth', methods: ["POST"])]
    public function refreshAuth(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] RefreshTokenRequestDTO $requestDto,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordEncoder,
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

    #[Route('/me', methods: ["GET"])]
    public function me(): Response
    {
        return $this->success($this->getUser(), context: ['groups' => ['user:me:read']]);
    }

    /**
     * @throws ExceptionInterface
     */
    private function arrayToDto(DenormalizerInterface $denormalizer, array $data): RefreshTokenDto
    {
        return $denormalizer->denormalize($data, RefreshTokenDto::class);
    }
}
