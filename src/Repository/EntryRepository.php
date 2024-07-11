<?php

namespace App\Repository;

use App\Entity\Edition;
use App\Entity\Entry;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entry>
 */
class EntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function getEntriesByUserAndEdition(int $userId, ?int $editionId = null): array
    {
        $builder = $this->createQueryBuilder('entry')
            ->andWhere('entry.user = :user')->setParameter('user', $userId)
            ->addOrderBy('entry.createdAt', 'DESC')
            ;

        if ($editionId) {
            $builder
                ->andWhere('entry.edition = :edition')->setParameter('edition', $editionId);
        } else {
            $builder->innerJoin('entry.edition', 'edition')
                ->andWhere(':now BETWEEN edition.startAt AND edition.endAt')->setParameter('now', new \DateTime())
                ;
        }

        return $builder->getQuery()->getResult();
    }

    public function getEntriesByGroupAndEdition(int $groupId, ?int $editionId = null): array
    {
        $builder = $this->createQueryBuilder('entry')
            ->innerJoin('entry.user', 'user')
            ->andWhere('user.group = :group')->setParameter('group', $groupId)
            ->addOrderBy('entry.createdAt', 'DESC')
        ;

        if ($editionId) {
            $builder
                ->andWhere('entry.edition = :edition')->setParameter('edition', $editionId);
        } else {
            $builder->innerJoin('entry.edition', 'edition')
                ->andWhere(':now BETWEEN edition.startAt AND edition.endAt')->setParameter('now', new \DateTime())
            ;
        }

        return $builder->getQuery()->getResult();
    }

    public function getTopTenJidByGroup(Group $group): array
    {
        return $this->createQueryBuilder('entries')
            ->innerJoin('entries.user', 'user')
            ->innerJoin('user.group', 'g')
            ->select('COUNT(DISTINCT entries.jid) as score, user.username, user.section')
            ->where('g = :group')->setParameter('group', $group)
            ->groupBy('user.username')
            ->orderBy('score', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getTopTenCountriesByGroup(Group $group): array
    {
        return $this->createQueryBuilder('entries')
            ->innerJoin('entries.user', 'user')
            ->innerJoin('user.group', 'g')
            ->select('COUNT(DISTINCT entries.country) as score, user.username, user.section')
            ->where('g = :group')->setParameter('group', $group)
            ->groupBy('user.username')
            ->orderBy('score', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getTopTenEntriesByGroup(Group $group): array
    {
        return $this->createQueryBuilder('entries')
            ->innerJoin('entries.user', 'user')
            ->innerJoin('user.group', 'g')
            ->select('COUNT(DISTINCT entries.id) as score, user.username, user.section')
            ->where('g = :group')->setParameter('group', $group)
            ->groupBy('user.username')
            ->orderBy('score', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getTopTenJidByEdition(Edition $edition): array
    {
        return $this->createQueryBuilder('entries')
            ->innerJoin('entries.user', 'user')
            ->innerJoin('user.group', 'g')
            ->innerJoin('entries.edition', 'edition')
            ->select("COUNT(DISTINCT entries.jid) as score, user.username, g.name as group, user.section")
            ->where('edition = :edition')->setParameter('edition', $edition)
            ->groupBy('user.username')
            ->orderBy('score', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getTopTenCountriesByEdition(Edition $edition): array
    {
        return $this->createQueryBuilder('entries')
            ->innerJoin('entries.user', 'user')
            ->innerJoin('user.group', 'g')
            ->innerJoin('entries.edition', 'edition')
            ->select('COUNT(DISTINCT entries.country) as score, user.username, g.name as group, user.section')
            ->where('edition = :edition')->setParameter('edition', $edition)
            ->groupBy('user.username')
            ->orderBy('score', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getTopTenEntriesByEdition(Edition $edition): array
    {
        return $this->createQueryBuilder('entries')
            ->innerJoin('entries.user', 'user')
            ->innerJoin('user.group', 'g')
            ->innerJoin('entries.edition', 'edition')
            ->select('COUNT(DISTINCT entries.id) as score, user.username, g.name as group, user.section')
            ->where('edition = :edition')->setParameter('edition', $edition)
            ->groupBy('user.username')
            ->orderBy('score', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult()
        ;
    }
    //    /**
    //     * @return Entry[] Returns an array of Entry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Entry
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
