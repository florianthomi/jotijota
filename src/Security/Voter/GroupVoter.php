<?php

namespace App\Security\Voter;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GroupVoter extends Voter
{
    public const MANAGE = 'GROUP_MANAGE';
    public const CREATE = 'GROUP_CREATE';
    public const LIST = 'GROUP_LIST';
    public const LIST_ALL = 'GROUP_LIST_ALL';


    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST, self::LIST_ALL, self::CREATE]) || (self::MANAGE === $attribute
            && $subject instanceof Group);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::LIST => !$user->getCoordinatedGroups()->isEmpty() || !$user->getCoordinatedEditions()->isEmpty(),
            self::MANAGE => $user->getCoordinatedGroups()->contains($subject) || $user->getCoordinatedEditions()->exists(fn($key,
                    Edition $edition) => $edition->getGroups()->contains($subject)),
            default => false,
        };
    }
}
