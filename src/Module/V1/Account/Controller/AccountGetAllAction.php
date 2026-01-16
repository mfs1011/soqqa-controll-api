<?php

namespace App\Module\V1\Account\Controller;

use App\Module\V1\Account\Mapper\AccountMapper;
use App\Module\V1\Account\Repository\AccountRepository;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts', methods: ['GET'])]
class AccountGetAllAction extends AbstractController
{
    public function __invoke(AccountRepository $repository, Request $request, AccountMapper $userMapper): Response
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
