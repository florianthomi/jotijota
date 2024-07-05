<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EntryVoter extends Voter
{
    public const NEW = 'ENTRY_NEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::NEW;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return (bool)$user?->getGroup()?->getCurrentEdition();
    }
}
