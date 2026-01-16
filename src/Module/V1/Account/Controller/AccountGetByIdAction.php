<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Controller;

use App\Module\V1\Account\Mapper\AccountMapper;
use App\Module\V1\Account\Repository\AccountRepository;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts/{id}', methods: ['GET'])]
class AccountGetByIdAction extends AbstractController
{
    public function __invoke(int $id, AccountRepository $repository, AccountMapper $mapper): Response
    {
        $account = $repository->findNotDeleted($id);

        return $this->itemResponse(
            data: $mapper->fromAccount($account),
        );
    }
}
