<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Event[] Returns an array of Car objects
    */
    public function eventsBySearchData($search): array
    {
        $entityManager = $this->getEntityManager();

        // DQL request( Doctrine Query Language )
        // SELECT event.*
        // FROM event
        // WHERE event.name LIKE '% $search %'
        $query = $entityManager->createQuery(
        'SELECT e
        FROM App\Entity\Event e
        WHERE e.name LIKE :search
        ');

        $query->setParameter('search', '%' . $search . '%');
        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Car[] Returns an array of Car objects
     * Custom method to retrieve all the cars related to an event
     */
    public function findByCarId($carId): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.car', 'c')
            ->where('c.id = :carId')
            ->setParameter('carId', $carId)
            ->getQuery()
            ->getResult();
    }
}
