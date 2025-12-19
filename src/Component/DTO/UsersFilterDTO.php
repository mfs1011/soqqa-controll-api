<?php

declare(strict_types=1);

namespace App\Component\DTO;

use \Symfony\Component\Validator\Constraints as Assert;

final readonly class UsersFilterDTO
{
    public function __construct(
        #[Assert\Positive]
        private int $page = 1,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        private int $limit = 20,

        private ?string $email = null,

        private ?bool $active = null,
    ) {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }
}
