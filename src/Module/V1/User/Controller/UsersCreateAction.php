<?php

namespace App\Module\V1\User\Controller;

use App\Module\V1\User\DTO\UserCreateDTO;
use App\Module\V1\User\Mapper\UserMapper;
use App\Module\V1\User\UseCase\CreateUser;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users', methods: ["POST"])]
class UsersCreateAction extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] UserCreateDTO $dto,
        CreateUser       $createUser,
        UserMapper       $userMapper
    ): Response
    {
        $user = $createUser->execute(
            $dto->getEmail(),
            $dto->getPassword()
        );

        return $this->itemResponse(
            data: $userMapper->fromUser($user),
            message: 'User successfully created.',
            statusCode: Response::HTTP_CREATED
        );
    }
}
