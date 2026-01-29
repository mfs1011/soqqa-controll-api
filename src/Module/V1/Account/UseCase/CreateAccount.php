<?php

namespace App\Module\V1\Account\UseCase;

use App\Module\V1\Account\DTO\Input\AccountCreateDTO;
use App\Module\V1\Account\Entity\Account;
use App\Module\V1\Account\Service\Factory\AccountFactory;
use App\Module\V1\Account\Service\Manager\AccountManager;

final readonly class CreateAccount
{
    public function __construct(
        private AccountFactory $factory,
        private AccountManager $manager
    ) {}

    public function execute(AccountCreateDTO $accountCreateDTO, int $userId): Account
    {
        $account = $this->factory->create($accountCreateDTO->getName(), $userId);
        $this->manager->save($account, true);

        return $account;
    }
}
