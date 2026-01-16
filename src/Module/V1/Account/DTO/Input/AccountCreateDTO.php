<?php

namespace App\Module\V1\Account\DTO\Input;

use App\Module\V1\Account\Entity\Account;
use App\Shared\Validator\Constraints\Unique;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class AccountCreateDTO
{
    public function __construct(
        #[Groups(['account:write'])]
        #[Unique(entity: Account::class, field: 'name')]
        private string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
