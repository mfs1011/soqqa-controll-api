<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\EventListener;

use App\Module\V1\Account\Event\TransferFailedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TransferFailedEvent::class)]
class TransferFailedEventListener
{
    public function __invoke(TransferFailedEvent $event): void
    {
        var_dump('Transfer failed');
    }
}
