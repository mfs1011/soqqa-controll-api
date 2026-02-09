<?php

declare(strict_types=1);

namespace App\Module\V1\Dashboard\DTO;

final readonly class DashboardTotalsDTO
{
    public function __construct(
        private int $totalBalance,
        private int $totalIncome,
        private int $totalExpense,
    )
    {
    }

    public function getTotalBalance(): int
    {
        return $this->totalBalance;
    }

    public function getTotalIncome(): int
    {
        return $this->totalIncome;
    }

    public function getTotalExpense(): int
    {
        return $this->totalExpense;
    }
}
