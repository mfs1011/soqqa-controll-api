<?php

namespace App\Component\User\Mapper;

use App\Component\User\DTO\UserMeDTO;
use App\Component\User\Entity\User;

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
