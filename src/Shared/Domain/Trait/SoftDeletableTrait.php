<?php

namespace App\Shared\Domain\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait SoftDeletableTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function delete(): void
    {
        if ($this->deletedAt === null) {
            $this->deletedAt = new DateTimeImmutable();
        }
    }

    public function recover(): void
    {
        $this->deletedAt = null;
    }
}
