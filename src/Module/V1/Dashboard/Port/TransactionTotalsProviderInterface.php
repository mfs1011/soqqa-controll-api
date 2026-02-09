<?php

namespace App\Module\V1\Dashboard\Port;

interface TransactionTotalsProviderInterface
{
    public function getTotalIncome(): int;
    public function getTotalExpense(): int;
}
