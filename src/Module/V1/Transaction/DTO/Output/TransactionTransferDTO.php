<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\DTO\Output;

use App\Module\V1\Transaction\Entity\Transaction;

final readonly class TransactionTransferDTO
{
    public function __construct(
        private Transaction $incomeTransaction,
        private Transaction $expenseTransaction,
    )
    {
    }

    public function getIncomeTransaction(): Transaction
    {
        return $this->incomeTransaction;
    }

    public function getExpenseTransaction(): Transaction
    {
        return $this->expenseTransaction;
    }
}
