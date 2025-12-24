<?php

namespace App\Component\User\DTO;

readonly class UserMeDTO
{
    public function __construct(
        private int    $id,
        private string $email,
        private array  $roles,
    ){
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
