<?php

namespace App\BusinessLogic\Services;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\EntryRepository;

readonly class Dashboard
{
    public function __construct(private EntryRepository $entryRepository)
    {
    }

    public function getStatsForUser(User $user): array
    {
        $entries = $this->entryRepository->getEntriesByUserAndEdition($user->getId());

        $countries = $jid = [];

        foreach ($entries as $entry) {
            if (!array_key_exists($entry->getCountry(), $countries)) {
                $countries[$entry->getCountry()] = 0;
            }

            $countries[$entry->getCountry()]++;

            if (!in_array($entry->getJid(), $jid)) {
                $jid[] = $entry->getJid();
            }
        }

        return [
            'entries' => [
                'label' => 'label.dashboard.entries',
                'value' => count($entries),
            ],
            'jid' => [
                'label' => 'label.dashboard.jid',
                'value' => count($jid),
            ],
            'countries' => [
                'label' => 'label.dashboard.countries',
                'value' => count($countries),
                'stats' => $countries
            ],
        ];
    }

    public function getStatsForGroup(Group $group): array
    {
        $entries = $this->entryRepository->getEntriesByGroupAndEdition($group->getId());

        $countries = $jid = [];

        foreach ($entries as $entry) {
            if (!array_key_exists($entry->getCountry(), $countries)) {
                $countries[$entry->getCountry()] = 0;
            }

            $countries[$entry->getCountry()]++;

            if (!in_array($entry->getJid(), $jid)) {
                $jid[] = $entry->getJid();
            }
        }

        return [
            'users' => [
                'label' => 'label.dashboard.users',
                'value' => $group->getUsers()->count()
            ],
            'entries' => [
                'label' => 'label.dashboard.entries',
                'value' => count($entries),
            ],
            'jid' => [
                'label' => 'label.dashboard.jid',
                'value' => count($jid),
            ],
            'countries' => [
                'label' => 'label.dashboard.countries',
                'value' => count($countries),
                'stats' => $countries
            ],
        ];
    }

    public function getStatsForEdition(Edition $edition): array
    {
        $entries = $this->entryRepository->findByEdition($edition);

        $countries = $jid = [];

        foreach ($entries as $entry) {
            if (!array_key_exists($entry->getCountry(), $countries)) {
                $countries[$entry->getCountry()] = 0;
            }

            $countries[$entry->getCountry()]++;

            if (!in_array($entry->getJid(), $jid)) {
                $jid[] = $entry->getJid();
            }
        }

        return [
            'groups' => [
                'label' => 'label.dashboard.groups',
                'value' => $edition->getGroups()->count()
            ],
            'users' => [
                'label' => 'label.dashboard.users',
                'value' => $edition->getGroups()->reduce(fn(int $acc, Group $group) => $acc + $group->getUsers()->count(), 0)
            ],
            'entries' => [
                'label' => 'label.dashboard.entries',
                'value' => count($entries),
            ],
            'jid' => [
                'label' => 'label.dashboard.jid',
                'value' => count($jid),
            ],
            'countries' => [
                'label' => 'label.dashboard.countries',
                'value' => count($countries),
                'stats' => $countries
            ],
        ];
    }

    public function getTopTenByGroup(Group $group)
    {
        return [
            'jid' => $this->entryRepository->getTopTenJidByGroup($group),
            'countries' => $this->entryRepository->getTopTenCountriesByGroup($group),
            'entries' => $this->entryRepository->getTopTenEntriesByGroup($group)
        ];
    }

    public function getTopTenByEdition(Edition $edition)
    {
        return [
            'jid' => $this->entryRepository->getTopTenJidByEdition($edition),
            'countries' => $this->entryRepository->getTopTenCountriesByEdition($edition),
            'entries' => $this->entryRepository->getTopTenEntriesByEdition($edition)
        ];
    }
}
