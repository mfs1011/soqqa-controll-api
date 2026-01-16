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
        private int $ownerId
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
}
