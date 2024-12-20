<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findBySearchAndDate(?string $search, ?string $date): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($search) {
            $qb->andWhere('e.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($date) {
            $startDate = \DateTime::createFromFormat('Y-m-d', $date);
            $endDate = (clone $startDate)->modify('+1 day');

            $qb->andWhere('e.date >= :start_date')
                ->andWhere('e.date < :end_date')
                ->setParameter('start_date', $startDate)
                ->setParameter('end_date', $endDate);
        }

        return $qb->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
    }




    //    /**
    //     * @return Event[] Returns an array of Event objects
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

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
