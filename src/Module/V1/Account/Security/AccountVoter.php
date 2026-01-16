<?php

namespace App\Module\V1\Account\Security;

use App\Module\V1\Account\Entity\Account;
use App\Module\V1\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

class AccountVoter extends Voter
{
    const string ACCOUNT_OWNER = 'ACCOUNT_OWNER';

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

        if (!$user instanceof User) {
            return false;
        }

        /** @var Account $account */
        $account = $subject;

        return $account->getOwner()->getId() === $user->getId() || $user->getRoles()->contains('ROLE_ADMIN');
    }
}

