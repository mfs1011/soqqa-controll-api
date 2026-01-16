<?php

namespace App\Shared\Domain\Interface;

use DateTimeImmutable;

interface SoftDeletableInterface
{
    public function isDeleted(): bool;
    public function getDeletedAt(): ?DateTimeImmutable;
    public function delete(): void;
    public function recover(): void;
}
