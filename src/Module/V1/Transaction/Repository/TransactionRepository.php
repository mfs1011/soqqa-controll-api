<?php

namespace App\Module\V1\Transaction\Repository;

use App\Module\V1\Transaction\Entity\Transaction;
use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use App\Shared\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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

    /**
     * @throws Exception
     */
    public function findYearlyIncomeExpenseTransactions(int $year): array
    {
        $conn = $this->getEntityManager()->getConnection();

        // PostgreSQL uchun Native SQL
        $sql = "
            SELECT
                EXTRACT(MONTH FROM created_at) as month,
                SUM(CASE WHEN type = :income THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = :expense THEN amount ELSE 0 END) as total_expense
            FROM transaction
            WHERE EXTRACT(YEAR FROM created_at) = :year
            GROUP BY month
            ORDER BY month ASC
        ";

        $dbData = $conn->fetchAllAssociative($sql, [
            'year'    => $year,
            'income'  => TransactionTypeEnum::Income->value,
            'expense' => TransactionTypeEnum::Expense->value,
        ]);

        return $this->formatYearlyReport($dbData, $year);
    }

    private function formatYearlyReport(array $dbData, int $year): array
    {
        $currentYear = (int) date('Y');

        // Agar tanlangan yil o'tgan yil bo'lsa, 12 oyni ko'rsatamiz.
        // Agar joriy yil bo'lsa, bazadagi oxirgi oy yoki joriy oydan kattasini olamiz.
        if ($year < $currentYear) {
            $monthLimit = 12;
        } elseif ($year === $currentYear) {
            // Bazadan kelgan oylarning eng kattasini topamiz
            $lastTransactionMonth = !empty($dbData) ? max(array_column($dbData, 'month')) : 0;
            $currentMonth = (int) date('n');

            // Qaysi biri katta bo'lsa shungacha ko'rsatamiz
            // (masalan, kishi oldindan kelajak sanasiga tranzaksiya kiritgan bo'lishi ham mumkin)
            $monthLimit = max($lastTransactionMonth, $currentMonth);
        } else {
            return []; // Kelajak yillar uchun bo'sh
        }

        $formattedReport = [];
        $indexedData = [];
        foreach ($dbData as $row) {
            $indexedData[(int)$row['month']] = $row;
        }

        for ($m = 1; $m <= $monthLimit; $m++) {
            $formattedReport[] = [
                'month'        => $m,
                'totalIncome'  => isset($indexedData[$m]) ? (int) $indexedData[$m]['total_income'] : 0,
                'totalExpense' => isset($indexedData[$m]) ? (int) $indexedData[$m]['total_expense'] : 0,
            ];
        }

        return $formattedReport;
    }

    public function findTotalIncomes(): int
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type = :type')
            ->setParameter('type', TransactionTypeEnum::Income)
            ->select('SUM(t.amount)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTotalExpenses(): int
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type = :type')
            ->setParameter('type', TransactionTypeEnum::Expense)
            ->select('SUM(t.amount)')
            ->getQuery()
            ->getSingleScalarResult();
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
