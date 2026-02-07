<?php

namespace App\Module\V1\Transaction\Repository;

use App\Module\V1\Transaction\Entity\Transfer;
use App\Shared\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transfer>
 */
class TransferRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transfer::class);
    }

    public function findAllWithPagination(array $queries): Paginator
    {
        $page = (int) ($queries['page'] ?? 1);
        $limit = (int) ($queries['limit'] ?? 10);
        $sort = $queries['sort'] ?? 'id';


        $qb = $this->createQueryBuilder('tr');
        $direction = strtoupper($queries['direction'] ?? 'DESC');

        $allowedSorts = [
            'id' => 'tr.id',
            'createdAt' => 'tr.createdAt',
            'amount' => 'tr.amount',
        ];

        if (!isset($allowedSorts[$sort])) {
            $sort = 'id';
        }

        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            $direction = 'DESC';
        }

        $qb->orderBy($allowedSorts[$sort], $direction);


        if (isset($queries['from_id'])) {
            $qb
                ->andWhere('tr.fromId = :from_id')
                ->setParameter('from_id', $queries['from_id']);
        }

        if (isset($queries['to_id'])) {
            $qb
                ->andWhere('tr.toId = :to_id')
                ->setParameter('to_id', $queries['to_id']);
        }

        if (isset($queries['type'])) {
            $qb
                ->andWhere('tr.type = :type')
                ->setParameter('type', $queries['type']);
        }

        return $this->paginate(
            queryBuilder: $qb,
            page: $page,
            itemsPerPage: $limit
        );
    }

    //    /**
    //     * @return Transfer[] Returns an array of Transfer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('tr')
    //            ->andWhere('tr.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transfer
    //    {
    //        return $this->createQueryBuilder('tr')
    //            ->andWhere('tr.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
