<?php

namespace App\Module\V1\User\UseCase;

use App\Module\V1\User\Entity\User;
use App\Module\V1\User\Service\Factory\UserFactory;
use App\Module\V1\User\Service\Manager\UserManager;

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

