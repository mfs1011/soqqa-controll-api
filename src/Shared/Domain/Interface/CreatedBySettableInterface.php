<?php

namespace App\Shared\Domain\Interface;

interface CreatedBySettableInterface
{
    public function setCreatedById(int $userId): static;
}
