<?php

namespace App\Module\V1\Account\Repository;

use App\Module\V1\Account\Entity\Account;
use App\Module\V1\Account\Exception\AccountNotFoundException;
use App\Shared\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 */
class AccountRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findTotalBalance(): int
    {
        return $this->createQueryBuilder('a')
            ->select('SUM(a.balance)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Paginator Returns a paginator of User objects
     */
    public function findAllWithPagination(array $queries): Paginator
    {
        $page = (int) ($queries['page'] ?? 1);
        $limit = (int) ($queries['limit'] ?? 10);

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
        ;

        if (isset($queries['search'])) {
            $qb->
            andWhere('a.name LIKE :search')
                ->setParameter('search', '%' . $queries['search'] . '%');
        }

        return $this->paginate(
            queryBuilder: $qb,
            page: $page,
            itemsPerPage: $limit
        );
    }

    public function findDeletedById(int $id): Account
    {
        return $this->withoutSoftDelete(function () use ($id) {
            $account = $this->createQueryBuilder('a')
                ->andWhere('a.id = :id')
                ->andWhere('a.deletedAt IS NOT NULL')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();

            if ($account === null) {
                throw new AccountNotFoundException('Account not found');
            }

            return $account;
        });
    }

    public function findNotDeleted(int $id): ?Account
    {
        $account = $this->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->andWhere('a.deletedAt IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($account === null) {
            throw new AccountNotFoundException('Account not found');
        }

        return $account;
    }

    public function findByUserAndId(int $id, int $ownerId): ?Account
    {
        $account = $this->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->andWhere('a.ownerId = :ownerId')
            ->andWhere('a.deletedAt IS NULL')
            ->setParameter('id', $id)
            ->setParameter('ownerId', $ownerId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($account === null) {
            throw new AccountNotFoundException('Account not found');
        }

        return $account;
    }


//    /**
    //     * @return Account[] Returns an array of Account objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Account
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
