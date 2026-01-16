<?php

namespace App\Module\V1\Account\UseCase;

use App\Module\V1\Account\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RecoverAccount
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function execute(Account $account): void
    {
        if (!$account->isDeleted()) {
            return;
        }

        $account->recover();

        $this->em->flush();
    }
}
