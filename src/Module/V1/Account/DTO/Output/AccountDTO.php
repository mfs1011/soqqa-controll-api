<?php

namespace App\Module\V1\Account\DTO\Output;

use Symfony\Component\Serializer\Attribute\Groups;

readonly class AccountDTO
{
    public function __construct(
        #[Groups(['user:read'])]
        private int $id,

        #[Groups(['user:read'])]
        private string $name,

        #[Groups(['user:read'])]
        private int $ownerId,

        #[Groups(['user:read'])]
        private int $balance,

        #[Groups(['user:read'])]
        private int $createdById,

        #[Groups(['user:read'])]
        private \DateTimeImmutable $createdAt
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getCreatedById(): int
    {
        return $this->createdById;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
