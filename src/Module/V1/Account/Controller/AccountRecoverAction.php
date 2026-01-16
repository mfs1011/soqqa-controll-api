<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Controller;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Account\UseCase\RecoverAccount;
use App\Shared\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/accounts/{id}/recover', methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
class AccountRecoverAction extends AbstractController
{
    public function __invoke(int $id, RecoverAccount $useCase, AccountRepository $accountRepository): Response
    {
        $account = $accountRepository->findDeletedById($id);
        $useCase->execute($account);

        return $this->success(['account_id' => $account->getId()], 'Account recovered.');
    }
}
