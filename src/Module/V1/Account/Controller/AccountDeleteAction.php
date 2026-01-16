<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Controller;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Account\UseCase\DeleteAccount;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/accounts/{id}', methods: ['DELETE'])]
class AccountDeleteAction extends AbstractController
{
    public function __invoke(int $id, DeleteAccount $useCase, AccountRepository $accountRepository): Response
    {
        $account = $accountRepository->findNotDeleted($id);

        $this->denyAccessUnlessGranted('ACCOUNT_OWNER', $account);

        $useCase->execute($account);

        return $this->success(['account_id' => $account->getId()], 'Account deleted');
    }
}
