<?php

namespace App\Component\User\Controller;

use App\Component\User\Repository\UserRepository;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UsersGetAllAction extends AbstractController
{
    #[Route('/', methods: ["GET"])]
    public function __invoke(UserRepository $repository, Request $request): JsonResponse
    {
        $queries = $request->query->all();
        $data = $repository->findAllWithPagination($queries);

        return $this->success($data, context: ['groups' => ['user:read']]);
    }
}
