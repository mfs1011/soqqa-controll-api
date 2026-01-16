<?php

namespace App\Module\V1\Account\Exception;

use App\Shared\Domain\Exception\NotFoundException;
use Throwable;

class AccountNotFoundException extends NotFoundException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
