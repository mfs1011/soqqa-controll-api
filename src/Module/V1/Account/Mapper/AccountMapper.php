<?php

namespace App\Module\V1\Account\Mapper;

use App\Module\V1\Account\DTO\Output\AccountDTO;
use App\Module\V1\Account\Entity\Account;
use App\Shared\DTO\PaginationDTO;
use App\Shared\DTO\PaginationResultDTO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AccountMapper
{
    public function fromPaginator(
        Paginator $paginator,
        int $page,
        int $limit
    ): PaginationResultDTO
    {
        $items = [];

        /** @var Account $account */
        foreach ($paginator as $account) {
            $items[] = new AccountDTO(
                $account->getId(),
                $account->getName(),
                $account->getOwnerId(),
                (int) $account->getBalance(),
                $account->getCreatedById(),
                $account->getCreatedAt(),
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

    public function fromAccount(Account $account): AccountDTO
    {
        return new AccountDTO(
            $account->getId(),
            $account->getName(),
            $account->getOwnerId(),
            $account->getBalance(),
            $account->getCreatedById(),
            $account->getCreatedAt(),
        );
    }
}
