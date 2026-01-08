<?php

declare(strict_types=1);

namespace App\Module\V1\User\Repository;

use App\Module\V1\User\Entity\User;
use App\Shared\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

        /**
         * @return Paginator Returns a paginator of User objects
         */
        public function findAllWithPagination(array $queries): Paginator
        {
            $page = (int) ($queries['page'] ?? 1);
            $limit = (int) ($queries['limit'] ?? 10);

            $qb = $this->createQueryBuilder('u')
                ->orderBy('u.id', 'DESC')
            ;

            if (isset($queries['search'])) {
                $qb->
                    andWhere('u.email LIKE :search')
                    ->setParameter('search', '%' . $queries['search'] . '%');
            }

            return $this->paginate(
                queryBuilder: $qb,
                page: $page,
                itemsPerPage: $limit
            );
        }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
