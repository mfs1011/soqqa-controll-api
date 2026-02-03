<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Controller;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Account\Security\AccountVoter;
use App\Module\V1\Transaction\DTO\Input\TransactionCreateIncomeOrExpenseDTO;
use App\Module\V1\Transaction\UseCase\CreateIncomeExpenseTransaction;
use App\Shared\Controller\AbstractController;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions/income_expense', methods: ["POST"])]
class TransactionCreateIncomeExpenseAction extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload(
            serializationContext: ['groups' => ['transaction:write']]
        )] TransactionCreateIncomeOrExpenseDTO $transactionData,
        CreateIncomeExpenseTransaction         $createTransaction,
        AccountRepository $accountRepository,

    ): Response
    {
        $account = $accountRepository->find($transactionData->getAccountId());

        if (!$account) {
            throw new NotFoundException('Account not found');
        }

        $transaction = $createTransaction->execute($transactionData, $account);

        $this->denyAccessUnlessGranted(AccountVoter::ACCOUNT_OWNER, $account);

        return $this->itemResponse(
            data: $transaction,
            message: 'Transaction successfully created.',
            statusCode: Response::HTTP_CREATED
        );
    }
}
