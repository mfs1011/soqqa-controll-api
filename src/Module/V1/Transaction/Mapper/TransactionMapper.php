<?php

namespace App\Module\V1\Transaction\Mapper;

use App\Module\V1\Transaction\DTO\Output\TransactionDTO;
use App\Module\V1\Transaction\Entity\Transaction;
use App\Shared\DTO\PaginationDTO;
use App\Shared\DTO\PaginationResultDTO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TransactionMapper
{
    public function fromPaginator(
        Paginator $paginator,
        int $page,
        int $limit
    ): PaginationResultDTO
    {
        $items = [];

        /** @var Transaction $transaction */
        foreach ($paginator as $transaction) {
            $items[] = new TransactionDTO(
                $transaction->getId(),
                $transaction->getAccountId(),
                $transaction->getType(),
                $transaction->getAmount(),
                $transaction->getDescription(),
                $transaction->getCreatedAt(),
            );
        }

        return new PaginationResultDTO(
            $items,
            new PaginationDTO(
                total: count($paginator),
                page: $page,
                lastPage: (int) ceil(count($paginator) / $limit),
                limit: $limit,
            )
        );
    }

    public function fromTransaction(Transaction $transaction): TransactionDTO
    {
        return new TransactionDTO(
            $transaction->getId(),
            $transaction->getAccountId(),
            $transaction->getType(),
            $transaction->getAmount(),
            $transaction->getDescription(),
            $transaction->getCreatedAt(),
        );
    }
}
