<?php

declare(strict_types=1);

namespace App\Module\V1\Dashboard\Controller;

use App\Module\V1\Dashboard\Application\Query\GetYearlyIncomeExpenseChartHandler;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/yearly-income-expense-transactions', methods: ['GET'])]
class DashboardYearlyIncomeExpenseAction extends AbstractController
{
    public function __invoke(Request $request, GetYearlyIncomeExpenseChartHandler $handler): Response
    {
        $year = (int) $request->query->get('year', date('Y'));
        $yearlyTransactions = $handler->handle($year);

        return $this->collectionResponse(
            data: $yearlyTransactions,
            message: 'Success data',
            statusCode: Response::HTTP_OK,
        );
    }
}
