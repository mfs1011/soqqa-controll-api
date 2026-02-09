<?php

declare(strict_types=1);

namespace App\Module\V1\Dashboard\Application\Query;

use App\Module\V1\Dashboard\Port\YearlyIncomeExpenseTransactionsInterface;
use App\Module\V1\Transaction\Entity\Transaction;

readonly class GetYearlyIncomeExpenseChartHandler
{
    public function __construct(private YearlyIncomeExpenseTransactionsInterface $yearlyIncomeExpenseTransactions)
    {
    }

    /** @return Transaction[] */
    public function handle(int $year): array
    {
        return $this->yearlyIncomeExpenseTransactions->getYearlyIncomeExpenseTransactions($year);
    }
}
