<?php

namespace App\Security\Voter;

use App\Entity\Edition;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const MANAGE = 'USER_MANAGE';
    public const DELETE = 'USER_DELETE';
    public const CREATE = 'USER_CREATE';
    public const LIST = 'USER_LIST';
    public const LIST_ALL = 'USER_LIST_ALL';


    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST, self::LIST_ALL, self::CREATE]) || (in_array($attribute, [self::MANAGE, self::DELETE])
                && $subject instanceof User);
    }

    /**
     * @param string         $attribute
     * @param User|null          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::CREATE => in_array('ROLE_ADMIN', $user->getRoles()),
            self::DELETE => $user !== $subject && $this->voteOnAttribute(self::MANAGE, $subject, $token),
            self::MANAGE => $user === $subject || $user->getCoordinatedGroups()->contains($subject->getGroup()) || $user->getCoordinatedEditions()->exists(fn($key, Edition $edition) => $user->getGroup()->getEditions()->contains($edition)),
            self::LIST => !$user->getCoordinatedGroups()->isEmpty() || !$user->getCoordinatedEditions()->isEmpty(),
            default => false,
        };
    }
}
