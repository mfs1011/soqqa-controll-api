<?php

declare(strict_types=1);

namespace App\Module\V1\Account\EventListener;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use App\Module\V1\Transaction\Event\TransactionIncomeExpenseCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TransactionIncomeExpenseCreatedEvent::class)]
final readonly class TransactionIncomeExpenseCreatedListener
{
    public function __construct(
        private AccountRepository $accountRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function __invoke(TransactionIncomeExpenseCreatedEvent $event): void
    {
        $transaction = $event->getTransaction();

        $account = $this->accountRepository->find($transaction->getAccountId());

        if (!$account) {
            // Bu holat NORMALDA bo‘lmasligi kerak
            // Lekin defensive programming
            return;
        }

        if ($transaction->getType() === TransactionTypeEnum::Income) {
            $account->deposit($transaction->getAmount());
        }

        if ($transaction->getType() === TransactionTypeEnum::Expense) {
            $account->withdraw($transaction->getAmount());
        }

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
