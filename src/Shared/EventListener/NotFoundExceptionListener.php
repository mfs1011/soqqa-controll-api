<?php

declare(strict_types=1);

namespace App\Shared\EventListener;

use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception')]
class NotFoundExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NotFoundException) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'status'  => 'error',
            'message' => $exception->getMessage() ?? "Resource not found",
        ], Response::HTTP_NOT_FOUND));
    }
}
