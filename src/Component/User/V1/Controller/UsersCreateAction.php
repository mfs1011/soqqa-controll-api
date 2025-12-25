<?php

namespace App\Component\User\V1\Controller;

use App\Component\User\V1\DTO\UserCreateDTO;
use App\Component\User\V1\Mapper\UserMapper;
use App\Component\User\V1\UseCase\CreateUser;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UsersCreateAction extends AbstractController
{
    #[Route('/users', methods: ["POST"])]
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
