<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function searchByFilter($data, $user)
    {
        $idUser = $user->getId();
        $idCampus = $data->campus ? $data->campus->getId() : null;
        $startDate = $data->startDate;
        $endDate = $data->endDate;
        $organizer = $data->eventOrganizer;
        $pastEvent = $data->pastEvent;

        $qb = $this->createQueryBuilder('e');

        $qb->select('e');

        // Filter on the campus
        if ($idCampus !== null) {
            $qb->andWhere('e.campus = :campus')
                ->setParameter('campus', $idCampus);
        }

        // Filter on the start date of the event 
        if ($startDate !== null) {
            $qb->andWhere("e.startDateTime >= :startDate")
                ->setParameter('startDate', $startDate);
        }

        // Filter on the end date of registrations
        if ($endDate !== null) {
            $qb->andWhere("e.endRegisterDate >= :endDate")
                ->setParameter("endDate", $endDate);
        }

        // Filter on the events of which I am the organizer
        if ($organizer) {
            $qb->andwhere('e.organizer = :pseudo')
                ->setParameter('pseudo', $idUser);
        };


        // Filter on past events
        if ($pastEvent) {
            $qb->andWhere("e.startDateTime < :now")
                ->setParameter("now", new \DateTime('now'));
        }

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
