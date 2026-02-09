<?php

declare(strict_types=1);

namespace App\Module\V1\Account\DTO\Output;

readonly class AccountTotalsDTO
{
    public function __construct(
        private int $id,
        private string $name,
        private int $amount,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
