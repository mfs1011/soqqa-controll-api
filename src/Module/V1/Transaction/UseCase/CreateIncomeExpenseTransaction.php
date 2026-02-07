<?php

namespace App\Module\V1\Transaction\UseCase;

use App\Module\V1\Account\Entity\Account;
use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Transaction\DTO\Input\TransactionCreateIncomeOrExpenseDTO;
use App\Module\V1\Transaction\Entity\Transaction;
use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use App\Module\V1\Transaction\Event\TransactionIncomeExpenseCreatedEvent;
use App\Module\V1\Transaction\Exception\InsufficientBalanceException;
use App\Module\V1\Transaction\Exception\InvalidAmountException;
use App\Module\V1\Transaction\Service\Factory\TransactionFactory;
use App\Shared\Domain\Exception\AccessDeniedException;
use App\Shared\Domain\Exception\NotFoundException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class CreateIncomeExpenseTransaction
{
    public function __construct(
        private EntityManagerInterface    $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private TransactionFactory     $transactionFactory,
    )
    {
    }

    public function execute(TransactionCreateIncomeOrExpenseDTO $createIncomeOrExpenseDTO, Account $account): Transaction
    {
        $transaction = $this->entityManager->wrapInTransaction(function () use ($createIncomeOrExpenseDTO, $account) {
            $this->validateTransaction($createIncomeOrExpenseDTO, $account);

            $transaction = $this->transactionFactory->create(
                $createIncomeOrExpenseDTO->getAccountId(),
                $createIncomeOrExpenseDTO->getType(),
                $createIncomeOrExpenseDTO->getAmount(),
                $createIncomeOrExpenseDTO->getDescription(),
                new DateTimeImmutable()
            );

            $this->entityManager->persist($transaction);

            return $transaction;
        });

        $this->eventDispatcher->dispatch(new TransactionIncomeExpenseCreatedEvent($transaction));

        return $transaction;
    }

    private function validateTransaction(
        TransactionCreateIncomeOrExpenseDTO $createIncomeOrExpenseDTO,
        Account                             $account
    ): void
    {
        if (
            $createIncomeOrExpenseDTO->getType() === TransactionTypeEnum::Expense &&
            $createIncomeOrExpenseDTO->getAmount() > $account->getBalance()
        ) {
            throw new InsufficientBalanceException();
        }
    }
}
