<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Controller;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Transaction\Mapper\TransactionMapper;
use App\Module\V1\Transaction\Repository\TransactionRepository;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions', methods: ["GET"])]
class TransactionGetAllAction extends AbstractController
{
    public function __invoke(
        Request $request,
        TransactionRepository $transactionRepository,
//        AccountRepository $accountRepository,
        TransactionMapper $transactionMapper
    ): Response
    {
        $queries = $request->query->all();
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);

        $data = $transactionRepository->findAllWithPagination($queries);
        $mapped = $transactionMapper->fromPaginator($data, $page, $limit);

        return $this->collectionResponse(
            data: $mapped->getItems(),
            meta: $mapped->getPagination(),
            message: 'Operation successful',
            statusCode: Response::HTTP_OK
        );
    }
}
