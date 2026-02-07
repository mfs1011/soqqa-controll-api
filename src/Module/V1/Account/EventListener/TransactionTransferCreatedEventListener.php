<?php

declare(strict_types=1);

namespace App\Module\V1\Account\EventListener;

use App\Module\V1\Account\Event\TransferFailedEvent;
use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Transaction\Event\TransferCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TransferCreatedEvent::class)]
final readonly class TransactionTransferCreatedEventListener
{
    public function __construct(
        private AccountRepository $accountRepository,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function __invoke(TransferCreatedEvent $createdEvent): void
    {
        $fromAccount = $this->accountRepository->find($createdEvent->getFromAccountId());
        $toAccount = $this->accountRepository->find($createdEvent->getToAccountId());

        if (!$fromAccount || !$toAccount) {
            $this->eventDispatcher->dispatch(new TransferFailedEvent($createdEvent->getTransferId()));
        }

        $this->entityManager->wrapInTransaction(function () use ($fromAccount, $toAccount, $createdEvent) {
            $fromAccount->withdraw($createdEvent->getAmount());
            $toAccount->deposit($createdEvent->getAmount());

            $this->entityManager->persist($fromAccount);
            $this->entityManager->persist($toAccount);
        });
    }
}
