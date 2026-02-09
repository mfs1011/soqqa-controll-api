<?php

declare(strict_types=1);

namespace App\Module\V1\Dashboard\Controller;

use App\Module\V1\Dashboard\Application\Query\GetDashboardTotalsHandler;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/totals', methods: ['GET'])]
class DashboardTotalsAction extends AbstractController
{
    public function __invoke(GetDashboardTotalsHandler $handler): Response
    {
        $totals = $handler->handle();

        return $this->itemResponse(
            data: $totals,
            message: 'Totals retrieved',
            statusCode: Response::HTTP_OK
        );
    }
}
