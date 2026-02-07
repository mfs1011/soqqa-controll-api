<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\DTO\Output;

final readonly class TransferDTO
{
    public function __construct(
        private int $id,
        private int $from_account_id,
        private int $to_account_id,
        private int $amount,
        private \DateTimeImmutable $createdAt,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFromAccountId(): int
    {
        return $this->from_account_id;
    }

    public function getToAccountId(): int
    {
        return $this->to_account_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
