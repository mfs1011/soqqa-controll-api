<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Service\Manager;

use App\Module\V1\Account\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;

readonly class AccountManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Account $data, bool $isNeedFlush = false): void
    {
        $this->entityManager->persist($data);

        if ($isNeedFlush) {
            $this->entityManager->flush();
        }
    }
}
