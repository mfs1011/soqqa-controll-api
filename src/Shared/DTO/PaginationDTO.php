<?php

namespace App\Shared\DTO;

readonly class PaginationDTO
{
    public function __construct(
        private int $total,
        private int $page,
        private int $lastPage,
        private int $limit
    ) {
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
