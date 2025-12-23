<?php

declare(strict_types=1);

namespace App\Component\User\DTO;

use App\Entity\User;
use App\Validator\Constraints\Unique;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserCreateDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Unique(
            entity: User::class,
            field: 'email',
        )]
        #[Groups(['user:write'])]
        private string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 6, minMessage: "Minimum {{ limit }} length")]
        #[Groups(['user:write'])]
        private string $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
