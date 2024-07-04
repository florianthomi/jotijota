<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::preUpdate, method: 'hashPassword', entity: User::class)]
#[AsEntityListener(event: Events::prePersist, method: 'hashPassword', entity: User::class)]
readonly class HashPasswordSubscriber
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function hashPassword(User $user): void
    {
        if (!$user->getPlainPassword()) {
            return;
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
    }
}
