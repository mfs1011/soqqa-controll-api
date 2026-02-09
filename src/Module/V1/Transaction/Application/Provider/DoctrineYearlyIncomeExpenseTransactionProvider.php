<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Application\Provider;

use App\Module\V1\Dashboard\Port\YearlyIncomeExpenseTransactionsInterface;
use App\Module\V1\Transaction\Entity\Transaction;
use App\Module\V1\Transaction\Repository\TransactionRepository;

readonly class DoctrineYearlyIncomeExpenseTransactionProvider implements YearlyIncomeExpenseTransactionsInterface
{
    public function __construct(private TransactionRepository $repository)
    {
    }

    /** @return Transaction[] */
    public function getYearlyIncomeExpenseTransactions(int $year): array
    {
        return $this->repository->findYearlyIncomeExpenseTransactions($year);
    }
}
