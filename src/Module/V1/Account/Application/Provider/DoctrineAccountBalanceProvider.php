<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Application\Provider;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Dashboard\Port\AccountBalanceProviderInterface;

final readonly class DoctrineAccountBalanceProvider implements AccountBalanceProviderInterface
{

    public function __construct(private AccountRepository $repository)
    {
    }

    public function getTotalBalance(): int
    {
        return $this->repository->findTotalBalance();
    }
}
