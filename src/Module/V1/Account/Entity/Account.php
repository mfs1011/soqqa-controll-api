<?php

namespace App\Module\V1\Account\Entity;

use App\Module\V1\Account\Repository\AccountRepository;
use App\Shared\Domain\Interface\CreatedAtSettableInterface;
use App\Shared\Domain\Interface\CreatedBySettableInterface;
use App\Shared\Domain\Interface\SoftDeletableInterface;
use App\Shared\Domain\Trait\CreatedAtTrait;
use App\Shared\Domain\Trait\CreatedByTrait;
use App\Shared\Domain\Trait\SoftDeletableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements SoftDeletableInterface, CreatedBySettableInterface, CreatedAtSettableInterface
{
    use SoftDeletableTrait;
    use CreatedAtTrait;
    use CreatedByTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $ownerId = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $balance = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function setOwnerId(int $ownerId): static
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    public function getBalance(): ?int
    {
        return (int) $this->balance;
    }

    public function deposit(int $amount): static
    {
        if ($amount <= 0) {
            throw new LogicException('Amount must be positive');
        }

        $this->balance = (string) ($this->getBalance() + $amount);

        return $this;
    }

    public function withdraw(int $amount): static
    {
        if ($amount <= 0) {
            throw new LogicException('Amount must be positive');
        }

        if ($amount > $this->getBalance()) {
            throw new LogicException('Insufficient balance');
        }

        $this->balance = (string) ($this->getBalance() - $amount);

        return $this;
    }
}
