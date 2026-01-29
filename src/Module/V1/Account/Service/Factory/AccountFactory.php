<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Service\Factory;

use App\Module\V1\Account\Entity\Account;

class AccountFactory
{
    public function create(string $name, int $ownerId): Account
    {
        $account = new Account();
        $account->setName($name);
        $account->setOwnerId($ownerId);

        return $account;
    }
}
