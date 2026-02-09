<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Application\Provider;

use App\Module\V1\Dashboard\Port\TransactionTotalsProviderInterface;
use App\Module\V1\Transaction\Repository\TransactionRepository;

readonly class DoctrineTransactionTotalsProvider implements TransactionTotalsProviderInterface
{
    public function __construct(
        private TransactionRepository $transactionRepository,
    )
    {
    }

    public function getTotalIncome(): int
    {
        return $this->transactionRepository->findTotalIncomes();
    }

    public function getTotalExpense(): int
    {
        return $this->transactionRepository->findTotalExpenses();
    }
}
