<?php

namespace App\Shared\Security\DoctrinePolicy;

use Doctrine\ORM\EntityManagerInterface;

interface FilterPolicyInterface
{
    public function supports(object $user): bool;
    public function apply(EntityManagerInterface $entityManager, object $user): void;
}
