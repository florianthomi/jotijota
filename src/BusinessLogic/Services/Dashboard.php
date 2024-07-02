<?php

namespace App\BusinessLogic\Services;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\EntryRepository;

class Dashboard
{
    public function __construct(private readonly EntryRepository $entryRepository)
    {
    }

    public function getStatsForUser(User $user): array
    {
        return [
            'entries' => [
                'label' => 'Entries',
                'value' => 0,
            ],
            'jid' => [
                'label' => 'JID',
                'value' => 0,
            ],
            'countries' => [
                'label' => 'countries',
                'value' => 0,
            ],
        ];
    }

    public function getStatsForGroup(Group $group): array
    {
        return [
            'users' => [
                'label' => 'Users',
                'value' => $group->getUsers()->count()
            ],
            'entries' => [
                'label' => 'Entries',
                'value' => 0,
            ],
            'jid' => [
                'label' => 'JID',
                'value' => 0,
            ],
            'countries' => [
                'label' => 'countries',
                'value' => 0,
            ]
        ];
    }

    public function getStatsForEdition(Edition $edition): array
    {
        return [
            'groups' => [
                'label' => 'Groups',
                'value' => $edition->getGroups()->count()
            ],
            'users' => [
                'label' => 'Users',
                'value' => $edition->getGroups()->reduce(fn(int $acc, Group $group) => $acc + $group->getUsers()->count(), 0)
            ],
            'entries' => [
                'label' => 'Entries',
                'value' => 0,
            ],
            'jid' => [
                'label' => 'JID',
                'value' => 0,
            ],
            'countries' => [
                'label' => 'countries',
                'value' => 0,
            ]
        ];
    }
}
