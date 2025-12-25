<?php

namespace App\Component\User\V1\Controller;

use App\Component\User\V1\Entity\User;
use App\Component\User\V1\Mapper\UserMeMapper;
use App\Shared\Controller\AbstractController;
use App\Shared\DTO\ItemResponseDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UsersAboutMeAction extends AbstractController
{
    #[Route('/users/me', methods: ["GET"])]
    public function me(
        #[CurrentUser] User $user,
        UserMeMapper $meMapper
    ): Response
    {
        return $this->itemResponse(
            data: $meMapper->fromEntity($user),
            message: 'Operation successful',
            statusCode: Response::HTTP_OK
        );
    }
}
