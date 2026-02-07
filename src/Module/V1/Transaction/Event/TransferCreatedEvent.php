<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Event;

readonly class TransferCreatedEvent
{
    public function __construct(
        private int $transferId,
        private int $fromAccountId,
        private int $toAccountId,
        private int $amount,
    ) {}

    public function getTransferId(): int
    {
        return $this->transferId;
    }

    public function getFromAccountId(): int
    {
        return $this->fromAccountId;
    }

    public function getToAccountId(): int
    {
        return $this->toAccountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
