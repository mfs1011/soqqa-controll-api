<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Service\Factory;

use App\Module\V1\Account\Entity\Account;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountFactory
{
    public function create(string $name, UserInterface $owner): Account
    {
        $account = new Account();
        $account->setName($name);
        $account->setOwner($owner);

        return $account;
    }
}
