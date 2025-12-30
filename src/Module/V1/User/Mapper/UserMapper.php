<?php

namespace App\Module\V1\User\Mapper;

use App\Module\V1\User\DTO\UserDTO;
use App\Module\V1\User\Entity\User;
use App\Shared\DTO\PaginationDTO;
use App\Shared\DTO\PaginationResultDTO;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserMapper
{
    public function fromPaginator(
        Paginator $paginator,
        int $page,
        int $limit
    ): PaginationResultDTO
    {
        $items = [];

        foreach ($paginator as $user) {
            $items[] = new UserDTO(
                $user->getId(),
                $user->getEmail(),
                $user->getRoles()
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

    public function fromUser(User $user): UserDTO
    {
        return new UserDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRoles()
        );
    }
}
