<?php

namespace App\Component\User\V1\Controller;

use App\Component\User\V1\DTO\UserAuthDTO;
use App\Component\User\V1\UseCase\AuthenticateUser;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UsersAuthAction extends AbstractController
{
    #[Route('/users/auth', methods: ["POST"])]
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] UserAuthDTO $authData,
        AuthenticateUser $authenticateUser
    ): Response
    {
        $token = $authenticateUser->execute($authData->getEmail(), $authData->getPassword());

        return $this->itemResponse(data: $token, message: 'Authentication successful', statusCode: Response::HTTP_OK);
    }
}
