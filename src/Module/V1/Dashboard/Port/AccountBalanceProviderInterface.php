<?php

namespace App\Module\V1\Dashboard\Port;

interface AccountBalanceProviderInterface
{
    public function getTotalBalance(): int;
}
