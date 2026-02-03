<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Enums;

use App\Shared\Domain\Interface\EnumerableInterface;

enum TransactionTypeEnum: string implements EnumerableInterface
{
    case Income = 'income';
    case Expense = 'expense';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
