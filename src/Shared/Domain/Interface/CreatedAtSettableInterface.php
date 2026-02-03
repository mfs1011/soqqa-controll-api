<?php

namespace App\Shared\Domain\Interface;

interface CreatedAtSettableInterface
{
    public function setCreatedAt(\DateTimeImmutable $createdAt): static;
}
