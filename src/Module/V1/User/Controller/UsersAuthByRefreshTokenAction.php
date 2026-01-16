<?php

namespace App\Module\V1\User\Controller;

use App\Module\V1\User\DTO\RefreshTokenRequestDTO;
use App\Module\V1\User\UseCase\RefreshAuthenticateUser;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users/refresh-auth', methods: ["POST"])]
class UsersAuthByRefreshTokenAction extends AbstractController
{
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
