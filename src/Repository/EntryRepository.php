<?php

namespace App\Repository;

use App\Entity\Entry;
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
