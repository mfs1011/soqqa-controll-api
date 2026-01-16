<?php

namespace App\Module\V1\User\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class InvalidRefreshTokenException extends UnauthorizedHttpException
{
    public function __construct()
    {
        parent::__construct(
            'Bearer',
            'Invalid or expired refresh token'
        );
    }
}
