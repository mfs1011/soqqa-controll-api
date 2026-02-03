<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\DTO\Input;

use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class TransactionCreateTransferDTO
{
    public function __construct(
        #[Groups(groups: ['transaction:write'])]
        #[Assert\NotEqualTo(value: 0)]
        #[Assert\Type('integer')]
        private int $fromAccountId,

        #[Groups(groups: ['transaction:write'])]
        #[Assert\NotEqualTo(value: 0)]
        #[Assert\Type('integer')]
        private int $toAccountId,

        #[Groups(groups: ['transaction:write'])]
        #[Assert\NotEqualTo(value: 0)]
        #[Assert\Type('integer')]
        private int $amount,

        #[Groups(groups: ['transaction:write'])]
        #[Assert\NotBlank]
        private string $description,
    ){
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

    public function getDescription(): string
    {
        return $this->description;
    }
}
