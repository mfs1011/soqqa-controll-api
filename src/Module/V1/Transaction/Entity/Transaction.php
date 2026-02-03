<?php

declare(strict_types=1);

namespace App\Module\V1\Transaction\Entity;

use App\Module\V1\Transaction\Enums\TransactionTypeEnum;
use App\Module\V1\Transaction\Repository\TransactionRepository;
use App\Shared\Domain\Interface\CreatedAtSettableInterface;
use App\Shared\Domain\Interface\CreatedBySettableInterface;
use App\Shared\Domain\Trait\CreatedAtTrait;
use App\Shared\Domain\Trait\CreatedByTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction implements CreatedAtSettableInterface, CreatedBySettableInterface
{
    use CreatedAtTrait;
    use CreatedByTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $accountId = null;

    #[ORM\Column(length: 30, enumType: TransactionTypeEnum::class)]
    private ?TransactionTypeEnum $type = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): static
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getType(): ?TransactionTypeEnum
    {
        return $this->type;
    }

    public function setType(TransactionTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
