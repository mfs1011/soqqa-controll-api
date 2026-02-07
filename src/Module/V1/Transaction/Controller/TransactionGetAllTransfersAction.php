<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Controller;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Transaction\Mapper\TransactionMapper;
use App\Module\V1\Transaction\Mapper\TransferMapper;
use App\Module\V1\Transaction\Repository\TransactionRepository;
use App\Module\V1\Transaction\Repository\TransferRepository;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions/transfers', methods: ["GET"])]
class TransactionGetAllTransfersAction extends AbstractController
{
    public function __invoke(
        Request $request,
        TransferRepository $transferRepository,
        TransferMapper $transferMapper
    ): Response
    {
        $queries = $request->query->all();
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);

        $data = $transferRepository->findAllWithPagination($queries);

        $mapped = $transferMapper->fromPaginator($data, $page, $limit);

        return $this->collectionResponse(
            data: $mapped->getItems(),
            meta: $mapped->getPagination(),
            message: 'Operation successful',
            statusCode: Response::HTTP_OK
        );
    }
}
