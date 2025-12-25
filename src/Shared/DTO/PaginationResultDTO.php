<?php

namespace App\Shared\DTO;

readonly class PaginationResultDTO
{
    public function __construct(
        private array $items,
        private PaginationDTO $pagination
    ) {
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getPagination(): PaginationDTO
    {
        return $this->pagination;
    }
}
