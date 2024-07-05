<?php

namespace App\EventSubscriber;

use App\Entity\Entry;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::preUpdate, method: 'setCountry', entity: Entry::class)]
#[AsEntityListener(event: Events::prePersist, method: 'setCountry', entity: Entry::class)]
readonly class EntryCountrySubscriber
{
    public function setCountry(Entry $entry): void
    {
        if (!$entry->getJid()) {
            return;
        }

        preg_match('/^[1-7]([A-Za-z]{2})[0-9]{2}[A-Za-z0-9]$/', $entry->getJid(), $matches);

        if (count($matches) > 1){
            $entry->setCountry(strtoupper($matches[1]));
        }
    }
}
