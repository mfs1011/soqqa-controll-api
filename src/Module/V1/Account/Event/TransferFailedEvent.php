<?php

declare(strict_types=1);

namespace App\Module\V1\Account\Event;

use App\Module\V1\Transaction\Entity\Transfer;

readonly class TransferFailedEvent
{
    public function __construct(
        private int $transferId,
    )
    {
    }

    public function getTransferId(): int
    {
        return $this->transferId;
    }
}
