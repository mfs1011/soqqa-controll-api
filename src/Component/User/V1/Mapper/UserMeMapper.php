<?php

namespace App\Component\User\V1\Mapper;

use App\Component\User\V1\DTO\UserMeDTO;
use App\Component\User\V1\Entity\User;

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
