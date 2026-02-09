<?php

namespace App\Module\V1\Dashboard\Port;

use App\Module\V1\Transaction\Entity\Transaction;

interface YearlyIncomeExpenseTransactionsInterface
{
    /** @return Transaction[] */
    public function getYearlyIncomeExpenseTransactions(int $year): array;
}
