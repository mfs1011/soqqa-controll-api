<?php

declare(strict_types=1);

namespace App\Component\User\V1\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class AuthException extends UnauthorizedHttpException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $message);
    }
}
