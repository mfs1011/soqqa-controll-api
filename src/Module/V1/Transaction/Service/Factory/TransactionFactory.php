<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Service\Factory;

use App\Module\V1\Transaction\Entity\Transaction;
use App\Module\V1\Transaction\Enums\TransactionTypeEnum;

class TransactionFactory
{
    public function create(
        int $accountId,
        TransactionTypeEnum $type,
        int $amount,
        string $description,
        \DateTimeImmutable $createdAt
    ): Transaction
    {
        return new Transaction()
            ->setAccountId($accountId)
            ->setType($type)
            ->setAmount($amount)
            ->setDescription($description)
            ->setCreatedAt($createdAt);
    }
}
