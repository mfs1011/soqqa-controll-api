<?php

namespace App\Module\V1\Transaction\Repository;

use App\Module\V1\Transaction\Entity\Transaction;
use App\Shared\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
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

        if (isset($queries['account_id'])) {
            $qb
                ->andWhere('t.accountId = :account_id')
                ->setParameter('account_id', $queries['account_id']);
        }

        if (isset($queries['search'])) {
            $qb
                ->andWhere('t.description LIKE :search')
                ->setParameter('search', '%' . $queries['search'] . '%');
        }

        if (isset($queries['type'])) {
            $qb
                ->andWhere('t.type = :type')
                ->setParameter('type', $queries['type']);
        }

        return $this->paginate(
            queryBuilder: $qb,
            page: $page,
            itemsPerPage: $limit
        );
    }

    //    /**
    //     * @return Transaction[] Returns an array of Transaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transaction
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
