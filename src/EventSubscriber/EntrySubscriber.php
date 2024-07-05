<?php

namespace App\EventSubscriber;

use App\Entity\Entry;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'check', entity: Entry::class)]
#[AsEntityListener(event: Events::prePersist, method: 'check', entity: Entry::class)]
readonly class EntrySubscriber
{
    public function check(Entry $entry): void
    {
        $answers = $entry->getAnswers();

        foreach ($answers as $answer) {
            if (!$answer->getAnswer() && !$answer->getQuestion()->isRequired()) {
                $entry->removeAnswer($answer);
            }
        }

        if (!$entry->getJid()) {
            return;
        }

        preg_match('/^[1-7]([A-Za-z]{2})[0-9]{2}[A-Za-z0-9]$/', $entry->getJid(), $matches);

        if (count($matches) > 1){
            $entry->setCountry(strtoupper($matches[1]));
        }
    }
}
