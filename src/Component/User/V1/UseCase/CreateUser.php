<?php

namespace App\Component\User\V1\UseCase;

use App\Component\User\V1\Entity\User;
use App\Component\User\V1\Service\Factory\UserFactory;
use App\Component\User\V1\Service\Manager\UserManager;

final readonly class CreateUser
{
    public function __construct(
        private UserFactory $factory,
        private UserManager $manager
    ) {}

    public function execute(string $email, string $password): User
    {
        $user = $this->factory->create($email, $password);
        $this->manager->save($user, true);

        return $user;
    }
}

