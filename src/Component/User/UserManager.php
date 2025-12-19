<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(User $data, bool $isNeedFlush = false): void
    {
        $this->entityManager->persist($data);

        if ($isNeedFlush) {
            $this->entityManager->flush();
        }
    }
}
