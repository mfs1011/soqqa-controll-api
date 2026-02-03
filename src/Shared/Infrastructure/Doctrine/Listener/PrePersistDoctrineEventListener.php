<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Listener;

use App\Module\V1\User\Entity\User;
use App\Shared\Domain\Interface\CreatedAtSettableInterface;
use App\Shared\Domain\Interface\CreatedBySettableInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsDoctrineListener(event: Events::prePersist, priority: 100)]
readonly class PrePersistDoctrineEventListener
{
    public function __construct(private Security $security)
    {
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof UserInterface) {
            return;
        }

        if ($entity instanceof CreatedAtSettableInterface) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

        if ($entity instanceof CreatedBySettableInterface) {

            $user = $this->security->getUser();

            if ($user !== null) {
                $entity->setCreatedById($user->getId());
            }
        }
    }
}
