<?php

namespace App\Shared\Domain\Trait;

use Doctrine\ORM\Mapping as ORM;

trait CreatedByTrait
{
    #[ORM\Column]
    private ?int $createdById = null;

    public function getCreatedById(): ?int
    {
        return $this->createdById;
    }

    public function setCreatedById(int $createdById): static
    {
        $this->createdById = $createdById;

        return $this;
    }
}
