<?php

namespace App\Shared\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BadRequestException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = Response::HTTP_FORBIDDEN, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
