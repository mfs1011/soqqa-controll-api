<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Message;

readonly class TransactionMessage
{
    public function __construct(
        private int $accountId,
        private int $amount
    )
    {
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
