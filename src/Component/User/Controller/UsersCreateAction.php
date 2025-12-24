<?php

namespace App\Component\User\Controller;

use App\Component\User\DTO\UserCreateDTO;
use App\Component\User\Service\Factory\UserFactory;
use App\Component\User\Service\Manager\UserManager;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UsersCreateAction extends AbstractController
{
    #[Route('/', methods: ["POST"])]
    public function __invoke(
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
