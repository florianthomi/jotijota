<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findGroupByUser(?int $userId)
    {
        $builder = $this->createQueryBuilder('g');

        if ($userId) {
            $builder
                ->leftJoin('g.editions', 'editions')
                ->orWhere(':user MEMBER OF editions.coordinators')
                ->orWhere(':user MEMBER OF g.coordinators')
                ->orWhere(':user MEMBER OF g.users')
                ->setParameter('user', $userId)
            ;
        }

        return $builder->getQuery()->getResult();
    }

    public function getVisibleGroups(): QueryBuilder
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.editions', 'edition')
            ->andWhere('g.visible = 1')
            ->andWhere('edition.visible = 1')
            ->andWhere(':now BETWEEN edition.subscriptionFrom AND edition.subscriptionTo')
            ->setParameter('now', new \DateTime())
            ->orderBy('g.name', 'ASC')
            ;
    }
}
