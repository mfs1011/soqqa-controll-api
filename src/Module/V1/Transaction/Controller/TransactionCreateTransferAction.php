<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Controller;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Transaction\DTO\Input\TransactionCreateTransferDTO;
use App\Module\V1\Transaction\UseCase\CreateTransferTransaction;
use App\Shared\Controller\AbstractController;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions/transfer', methods: ["POST"])]
class TransactionCreateTransferAction extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['transaction:write']]
        )] TransactionCreateTransferDTO $transactionData,
        CreateTransferTransaction $createTransferTransaction,
        AccountRepository $accountRepository

    ): Response
    {
        $fromAccount = $accountRepository->find($transactionData->getFromAccountId());
        $toAccount = $accountRepository->find($transactionData->getToAccountId());

        if (!$fromAccount || !$toAccount) {
            throw new NotFoundException('Account not found');
        }

        $transaction = $createTransferTransaction->execute(
            $transactionData,
            $this->getUser()->getId(),
            $fromAccount,
            $toAccount
        );

        return $this->itemResponse(
            data: $transaction,
            message: 'Transaction successfully created.',
            statusCode: Response::HTTP_CREATED
        );
    }
}
