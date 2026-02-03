<?php

namespace App\Module\V1\Transaction\DTO\Output;

use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use Symfony\Component\Serializer\Attribute\Groups;

final readonly class TransactionDTO
{
    public function __construct(
        #[Groups(['transaction:read'])]
        private int $id,

        #[Groups(['transaction:read'])]
        private int $accountId,

        #[Groups(['transaction:read'])]
        private TransactionTypeEnum $type,

        #[Groups(['transaction:read'])]
        private int $amount,

        #[Groups(['transaction:read'])]
        private string $description,

        #[Groups(['transaction:read'])]
        private \DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
