<?php

namespace App\Module\V1\Transaction\UseCase;

use App\Module\V1\Account\Entity\Account;
use App\Module\V1\Transaction\DTO\Input\TransactionCreateTransferDTO;
use App\Module\V1\Transaction\Entity\Transfer;
use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use App\Module\V1\Transaction\Event\TransferCreatedEvent;
use App\Module\V1\Transaction\Exception\InsufficientBalanceException;
use App\Module\V1\Transaction\Service\Factory\TransactionFactory;
use App\Shared\Domain\Exception\AccessDeniedException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class CreateTransferTransaction
{
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private EventDispatcherInterface    $eventDispatcher,
        private TransactionFactory          $transactionFactory,
    )
    {
    }

    public function execute(
        TransactionCreateTransferDTO $transactionCreateTransferDTO,
        int $userId,
        Account $fromAccount,
        Account $toAccount,
    ): Transfer
    {
        // TODO
        // DTO dan from, to, amount, description keladi
        // 1. from account balance'idan amount katta bo'lmasligi kerak
        // 2. transaction yaratilib keyin account balance'larini update qilish kerak
        // 3.

        /** @var Transfer $transfer */
        $transfer = $this->entityManager->wrapInTransaction(function () use (
            $toAccount,
            $fromAccount,
            $transactionCreateTransferDTO,
            $userId
        ) {
            $this->validateTransaction($transactionCreateTransferDTO, $userId, $fromAccount, $toAccount);

            $fromTransaction = $this->transactionFactory->create(
                $transactionCreateTransferDTO->getFromAccountId(),
                TransactionTypeEnum::Expense,
                $transactionCreateTransferDTO->getAmount(),
                $transactionCreateTransferDTO->getDescription(),
                new DateTimeImmutable()
            );

            $this->entityManager->persist($fromTransaction);
            $fromAccount->withdraw($transactionCreateTransferDTO->getAmount());
            $this->entityManager->persist($fromAccount);

            $toTransaction = $this->transactionFactory->create(
                $transactionCreateTransferDTO->getToAccountId(),
                TransactionTypeEnum::Income,
                $transactionCreateTransferDTO->getAmount(),
                $transactionCreateTransferDTO->getDescription(),
                new DateTimeImmutable()
            );

            $this->entityManager->persist($toTransaction);
            $toAccount->deposit($transactionCreateTransferDTO->getAmount());
            $this->entityManager->persist($toAccount);

            $transfer = new Transfer();
            $transfer->setFromId($fromAccount->getId());
            $transfer->setToId($toAccount->getId());
            $transfer->setAmount($transactionCreateTransferDTO->getAmount());
            $transfer->setDescription($transactionCreateTransferDTO->getDescription());

            $this->entityManager->persist($transfer);

            return $transfer;
        });

        $this->eventDispatcher->dispatch(new TransferCreatedEvent(
            transferId: $transfer->getId(),
            fromAccountId: $transfer->getFromId(),
            toAccountId: $transfer->getToId(),
            amount: $transfer->getAmount(),
        ));

        return $transfer;
    }

    private function validateTransaction(
        TransactionCreateTransferDTO        $transactionCreateTransferDTO,
        int                                 $userId,
        Account                             $fromAccount,
        Account                             $toAccount,
    ): void
    {
        if ($fromAccount->getId() === $toAccount->getId()) {
            throw new LogicException('Cannot transfer to the same account');
        }

        if ($fromAccount->getOwnerId() !== $userId) {
            throw new AccessDeniedException('You are not allowed to create transactions for this account.');
        }

        if ($transactionCreateTransferDTO->getAmount() > $fromAccount->getBalance()) {
            throw new InsufficientBalanceException();
        }
    }
}
