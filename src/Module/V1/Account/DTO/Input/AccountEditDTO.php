<?php

declare(strict_types=1);

namespace App\Module\V1\Account\DTO\Input;

use Symfony\Component\Serializer\Attribute\Groups;

final readonly class AccountEditDTO
{
    public function __construct(
        #[Groups(['account:write'])]
        private string $name
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
