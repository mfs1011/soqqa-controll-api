<?php

namespace App\Module\V1\User\Mapper;

use App\Module\V1\User\DTO\UserMeDTO;
use App\Module\V1\User\Entity\User;

final class UserMeMapper
{
    public function fromEntity(User $user): UserMeDTO
    {
        return new UserMeDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRoles()
        );
    }
}
