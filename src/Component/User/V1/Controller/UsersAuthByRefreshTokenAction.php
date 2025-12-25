<?php

namespace App\Component\User\V1\Controller;

use App\Component\User\V1\DTO\RefreshTokenRequestDTO;
use App\Component\User\V1\UseCase\RefreshAuthenticateUser;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UsersAuthByRefreshTokenAction extends AbstractController
{
    #[Route('/users/refresh-auth', methods: ["POST"])]
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['user:write']]
        )] RefreshTokenRequestDTO $requestDto,
        RefreshAuthenticateUser $refreshAuthenticateUser
    ): Response
    {
        $tokens = $refreshAuthenticateUser->execute(
            $requestDto->getRefreshToken()
        );

        return $this->itemResponse(
            data: $tokens,
            message: 'Authentication successful',
            statusCode: Response::HTTP_OK
        );
    }
}
