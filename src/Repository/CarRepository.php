<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function add(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Car[] Returns an array of Car objects
     * Custom method to retrieve all the cars related to an event
     */
    public function findByEventId($eventId): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.events', 'e')
            ->where('e.id = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Car[] Returns an array of Car objects
     * Custom method to retrieve all the cars related to an event
     */
    public function findByMakeId($makeId): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.make', 'm')
            ->where('m.id = :makeId')
            ->setParameter('makeId', $makeId)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Car
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
