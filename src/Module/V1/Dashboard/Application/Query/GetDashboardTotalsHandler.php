<?php

declare(strict_types=1);

namespace App\Module\V1\Dashboard\Application\Query;

use App\Module\V1\Dashboard\DTO\DashboardTotalsDTO;
use App\Module\V1\Dashboard\Port\AccountBalanceProviderInterface;
use App\Module\V1\Dashboard\Port\TransactionTotalsProviderInterface;

readonly class GetDashboardTotalsHandler
{
    public function __construct(
        private AccountBalanceProviderInterface $accountBalanceProvider,
        private TransactionTotalsProviderInterface $transactionTotalsProvider
    )
    {
    }

    public function handle(): DashboardTotalsDTO
    {
        $balance = $this->accountBalanceProvider->getTotalBalance();
        $income = $this->transactionTotalsProvider->getTotalIncome();
        $expense = $this->transactionTotalsProvider->getTotalExpense();

        return new DashboardTotalsDTO(
            $balance,
            $income,
            $expense
        );
    }
}
