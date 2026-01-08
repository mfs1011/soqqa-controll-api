<?php

namespace App\Module\V1\User\Controller;

use App\Module\V1\User\Mapper\UserMapper;
use App\Module\V1\User\Repository\UserRepository;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UsersGetAllAction extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/users', methods: ['GET'])]
    public function __invoke(UserRepository $repository, Request $request, UserMapper $userMapper): JsonResponse
    {
        $queries = $request->query->all();
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);

        $data = $repository->findAllWithPagination($queries);
        $mapped = $userMapper->fromPaginator($data, $page, $limit);

        return $this->collectionResponse(
            data: $mapped->getItems(),
            meta: $mapped->getPagination(),
            message: 'Operation successful',
            statusCode: Response::HTTP_OK
        );
    }
}
