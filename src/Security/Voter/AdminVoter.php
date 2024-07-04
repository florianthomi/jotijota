<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

#[Autoconfigure(tags: [['security.voter' => ['priority' => 3000]]])]
class AdminVoter extends Voter
{
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
       return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return in_array(self::ROLE_SUPER_ADMIN, $user->getRoles());
    }
}
