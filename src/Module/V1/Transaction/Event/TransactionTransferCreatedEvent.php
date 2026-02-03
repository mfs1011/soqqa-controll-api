<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Event;

use App\Module\V1\Transaction\Entity\Transaction;

readonly class TransactionTransferCreatedEvent
{
    public function __construct(
        private Transaction $fromTransaction,
        private Transaction $toTransaction,
    ) {}

    public function getFromTransaction(): Transaction
    {
        return $this->fromTransaction;
    }

    public function getToTransaction(): Transaction
    {
        return $this->toTransaction;
    }
}
