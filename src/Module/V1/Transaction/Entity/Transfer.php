<?php

namespace App\Module\V1\Transaction\Entity;

use App\Module\V1\Transaction\Repository\TransferRepository;
use App\Shared\Domain\Interface\CreatedAtSettableInterface;
use App\Shared\Domain\Interface\CreatedBySettableInterface;
use App\Shared\Domain\Trait\CreatedAtTrait;
use App\Shared\Domain\Trait\CreatedByTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransferRepository::class)]
class Transfer implements CreatedAtSettableInterface, CreatedBySettableInterface
{
    use CreatedAtTrait;
    use CreatedByTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $fromId = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $toId = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromId(): ?int
    {
        return (int) $this->fromId;
    }

    public function setFromId(int $fromId): static
    {
        $this->fromId = (string) $fromId;

        return $this;
    }

    public function getToId(): ?int
    {
        return (int) $this->toId;
    }

    public function setToId(int $toId): static
    {
        $this->toId = (string) $toId;

        return $this;
    }

    public function getAmount(): ?int
    {
        return (int) $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = (string) $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
