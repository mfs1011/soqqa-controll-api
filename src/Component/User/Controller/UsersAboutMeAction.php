<?php

namespace App\Component\User\Controller;

use App\Component\User\Entity\User;
use App\Component\User\Mapper\UserMeMapper;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UsersAboutMeAction extends AbstractController
{
    #[Route('/me', methods: ["GET"])]
    public function me(
        #[CurrentUser] User $user,
        UserMeMapper $meMapper
    ): Response
    {
        return $this->success($meMapper->fromEntity($user));
    }
}
