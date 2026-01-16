<?php

namespace App\Module\V1\User\Controller;

use App\Module\V1\User\Entity\User;
use App\Module\V1\User\Mapper\UserMeMapper;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/users/me', methods: ["GET"])]
class UsersAboutMeAction extends AbstractController
{
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
