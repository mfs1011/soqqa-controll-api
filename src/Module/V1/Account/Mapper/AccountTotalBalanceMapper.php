<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Mapper;

use App\Module\V1\Account\DTO\Output\AccountTotalsDTO;

class AccountTotalBalanceMapper
{
    public function fromBalance(int $balance): array
    {
        $data = [];
        $data[] =  new AccountTotalsDTO(
            id: 1,
            name: 'Total Balance',
            amount: $balance,
        );

        return $data;
    }
}
