<?php

namespace App\Module\V1\Account\UseCase;

use App\Module\V1\Account\DTO\Input\AccountCreateDTO;
use App\Module\V1\Account\Entity\Account;
use App\Module\V1\Account\Service\Factory\AccountFactory;
use App\Module\V1\Account\Service\Manager\AccountManager;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class CreateAccount
{
    public function __construct(
        private AccountFactory $factory,
        private AccountManager $manager
    ) {}

    public function execute(AccountCreateDTO $accountCreateDTO, UserInterface $currentUser): Account
    {
        $account = $this->factory->create($accountCreateDTO->getName(), $currentUser);
        $this->manager->save($account, true);

        return $account;
    }
}
