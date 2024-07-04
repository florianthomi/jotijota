<?php

namespace App\Security\Voter;

use App\Entity\Edition;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EditionVoter extends Voter
{
    public const MANAGE = 'EDITION_MANAGE';
    public const CREATE = 'EDITION_CREATE';
    public const LIST = 'EDITION_LIST';
    public const LIST_ALL = 'EDITION_LIST_ALL';


    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST, self::LIST_ALL, self::CREATE]) || (self::MANAGE === $attribute
                && $subject instanceof Edition);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::LIST => !$user->getCoordinatedEditions()->isEmpty(),
            self::MANAGE => $user->getCoordinatedEditions()->contains($subject),
            default => false,
        };
    }
}
