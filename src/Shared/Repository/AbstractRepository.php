<?php

declare(strict_types=1);

namespace App\Shared\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function paginate(QueryBuilder $queryBuilder, $page = 1, $itemsPerPage = 10): Paginator
    {
        return new Paginator(
            $queryBuilder
                ->setFirstResult(($page - 1) * $itemsPerPage)
                ->setMaxResults($itemsPerPage)
                ->getQuery()
        );
    }

    protected function withoutSoftDelete(callable $callback): mixed
    {
        $em = $this->getEntityManager();
        $filters = $em->getFilters();

        $wasEnabled = $filters->isEnabled('soft_delete');

        if ($wasEnabled) {
            $filters->disable('soft_delete');
        }

        try {
            return $callback();
        } finally {
            if ($wasEnabled) {
                $filters->enable('soft_delete');
            }
        }
    }


    public function getSQLQuery(QueryBuilder $queryBuilder, $page = 1, $itemsPerPage = 10): string
    {
        return
            $queryBuilder
                ->setFirstResult(($page - 1) * $itemsPerPage)
                ->setMaxResults($itemsPerPage)
                ->getQuery()
                ->getSQL();
    }
}
