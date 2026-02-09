<?php

namespace App\Module\V1\Transaction\Mapper;

use App\Module\V1\Transaction\DTO\Output\TransferDTO;
use App\Module\V1\Transaction\Entity\Transfer;
use App\Shared\DTO\PaginationDTO;
use App\Shared\DTO\PaginationResultDTO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TransferMapper
{
    public function fromPaginator(
        Paginator $paginator,
        int $page,
        int $limit
    ): PaginationResultDTO
    {
        $items = [];

        /** @var Transfer $transfer */
        foreach ($paginator as $transfer) {
            $items[] = new TransferDTO(
                $transfer->getId(),
                $transfer->getFromId(),
                $transfer->getToId(),
                $transfer->getAmount(),
                $transfer->getDescription() ?? '',
                $transfer->getCreatedAt(),
                $transfer->getCreatedById(),
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

    public function fromTransfer(Transfer $transfer): TransferDTO
    {
        return new TransferDTO(
            $transfer->getId(),
            $transfer->getFromId(),
            $transfer->getToId(),
            $transfer->getAmount(),
            $transfer->getDescription() ?? '',
            $transfer->getCreatedAt(),
            $transfer->getCreatedById(),
        );
    }
}
