<?php

namespace App\Module\V1\Account\Security;

use App\Module\V1\Account\Entity\Account;
use App\Module\V1\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountVoter extends Voter
{
    public const string ACCOUNT_OWNER = 'ACCOUNT_OWNER';
    public const string IS_ADMIN = 'IS_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ACCOUNT_OWNER && $subject instanceof Account;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Account $account */
        $account = $subject;

        return match ($attribute) {
            self::IS_ADMIN => $this->isAdmin($user),
            self::ACCOUNT_OWNER => $this->isOwner($user, $subject),
            default => false,
        };
    }

    private function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    private function isOwner(UserInterface $user, Account $account): bool
    {
        return $account->getOwnerId() === $user->getId();
    }
}

