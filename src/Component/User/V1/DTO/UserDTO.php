<?php

namespace App\Component\User\V1\DTO;

use Symfony\Component\Serializer\Attribute\Groups;

readonly class UserDTO
{
    public function __construct(
        #[Groups(['user:read'])]
        private int $id,

        #[Groups(['user:read'])]
        private string $email,

        #[Groups(['user:read'])]
        private array $roles
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
