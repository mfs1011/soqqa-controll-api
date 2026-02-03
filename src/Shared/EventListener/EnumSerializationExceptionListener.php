<?php

declare(strict_types=1);

namespace App\Shared\EventListener;

use App\Shared\Domain\Interface\EnumerableInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

#[AsEventListener(event: 'kernel.exception', priority: 10)]
class EnumSerializationExceptionListener
{
    private const string DEFAULT_DETAIL = "The provided value is invalid for this field.";

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$this->isSerializerError($exception)) {
            return;
        }

        $detail = $this->resolveDetailMessage($exception->getMessage());

        $event->setResponse(new JsonResponse([
            'status'  => 'error',
            'message' => 'Invalid Request Format',
            'detail'  => $detail,
        ], Response::HTTP_BAD_REQUEST));
    }

    private function isSerializerError(\Throwable $exception): bool
    {
        return $exception instanceof InvalidArgumentException ||
            $exception->getPrevious() instanceof InvalidArgumentException;
    }

    private function resolveDetailMessage(string $rawMessage): string
    {
        if (preg_match('/type (?P<class>[a-zA-Z0-9\\\\_]+Enum)/', $rawMessage, $matches)) {
            $enumClass = $matches['class'];

            if (class_exists($enumClass) && is_subclass_of($enumClass, EnumerableInterface::class)) {
                return sprintf(
                    "Invalid value. Allowed values are: '%s'.",
                    implode("', '", $enumClass::values())
                );
            }
        }

        return self::DEFAULT_DETAIL;
    }
}
