<?php

declare(strict_types=1);

namespace App\Controller;

use App\Component\DTO\UserCreateDTO;
use App\Component\User\UserFactory;
use App\Component\User\UserManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users', name: 'users')]
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
}
