<?php

declare(strict_types=1);

namespace App\Shared\Security\DoctrinePolicy;

use App\Shared\Infrastructure\Doctrine\Filter\CreatedByFilter;
use Doctrine\ORM\EntityManagerInterface;

class CreatedByFilterPolicy implements FilterPolicyInterface
{

    public function supports(object $user): bool
    {
        return method_exists($user, 'getRoles')
            && !in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    public function apply(EntityManagerInterface $entityManager, object $user): void
    {
        $filters = $entityManager->getFilters();

        if (!$filters->isEnabled(CreatedByFilter::CREATED_BY_FILTER)) {
            $filter = $filters->enable(CreatedByFilter::CREATED_BY_FILTER);
            $filter->setParameter('createdById', $user->getId());
        }
    }
}
