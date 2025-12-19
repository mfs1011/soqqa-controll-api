<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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
            $page = $queries['page'] ?? 1;
            $limit = $queries['limit'] ?? 10;

            $qb = $this->createQueryBuilder('u');

            if (isset($queries['search'])) {
                $qb->
                    andWhere('u.email LIKE :search')
                    ->setParameter('search', '%' . $queries['search'] . '%');
            }

            return $this->paginate(
                queryBuilder: $this->createQueryBuilder('u'),
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
