<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Service\Manager;

use App\Module\V1\Account\Entity\Account;
use App\Module\V1\Transaction\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

readonly class TransactionManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Transaction $data, bool $isNeedFlush = false): void
    {
        $this->entityManager->persist($data);

        if ($isNeedFlush) {
            $this->entityManager->flush();
        }
    }
}
