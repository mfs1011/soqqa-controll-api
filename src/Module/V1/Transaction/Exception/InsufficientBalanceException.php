<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InsufficientBalanceException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Insufficient balance.');
    }
}
