<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\DTO\Input;

use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class TransactionCreateIncomeOrExpenseDTO
{
    public function __construct(
        #[Groups(groups: ['transaction:write'])]
        private int $accountId,

        #[Groups(groups: ['transaction:write'])]
        #[Assert\Choice(choices: [
            TransactionTypeEnum::Income,
            TransactionTypeEnum::Expense
        ])]
        private TransactionTypeEnum $type,

        #[Groups(groups: ['transaction:write'])]
        #[Assert\Positive]
        #[Assert\NotEqualTo(value: 0)]
        private int $amount,

        #[Groups(groups: ['transaction:write'])]
        private string $description,
    ){
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
}
