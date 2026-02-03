<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Event;

use App\Module\V1\Transaction\Entity\Transaction;

readonly class TransactionIncomeExpenseCreatedEvent
{
    public function __construct(
        private Transaction $transaction
    ) {}

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
