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

    /**
    * @return Car[] Returns an array of Car objects
    */
    public function carsBySearchData($search): array
    {
        $entityManager = $this->getEntityManager();

        // requete en DQL ( Doctrine Query Language )
        // SELECT car.*, make.*
        // FROM car
        // INNER JOIN make ON car.make_id = make.id
        // WHERE car.model LIKE '% $search %' OR make.name LIKE  '% $search %'
        $query = $entityManager->createQuery(
        'SELECT c
        FROM App\Entity\Car c
        INNER JOIN App\Entity\Make m WITH c.make = m.id
        WHERE c.model LIKE :search
        OR m.name LIKE :search
        ');

        $query->setParameter('search', '%' . $search . '%');
        // returns an array of Product objects
        return $query->getResult();
    }
}
