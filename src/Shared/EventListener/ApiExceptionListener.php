<?php

declare(strict_types=1);

namespace App\Shared\EventListener;

use App\Security\Exception\TokenExpiredException;
use App\Security\Exception\TokenInvalidException;
use App\Shared\Domain\Exception\AccessDeniedException;
use App\Shared\Domain\Exception\BadRequestException;
use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\Exception\NotFoundException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception')]
final class ApiExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $response = match (true) {
            $throwable instanceof TokenExpiredException =>
                $this->json(
                    'Token expired',
                    Response::HTTP_BAD_REQUEST
                ),
            $throwable instanceof TokenInvalidException =>
                $this->json(
                    'Token invalid',

                    Response::HTTP_FORBIDDEN
                ),
            $throwable instanceof DomainException =>
                $this->json(
                    $throwable->getMessage() ?? 'Domain exception' ,
                    Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            $throwable instanceof AccessDeniedException =>
                $this->json(
                    $throwable->getMessage() ?? "Access Denied",
                    Response::HTTP_FORBIDDEN
                ),
            $throwable instanceof BadRequestException =>
                $this->json(
                    $throwable->getMessage() ?? "Bad Request",
                    Response::HTTP_BAD_REQUEST
                ),
            $throwable instanceof NotFoundException =>
                $this->json(
                    $throwable->getMessage() ?? "Not found",
                    Response::HTTP_NOT_FOUND
                ),
            default => null,
        };

        if ($response !== null) {
            $event->setResponse($response);
        }
    }

    private function json(string $message, int $status): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $message,
        ], $status);
    }
}
