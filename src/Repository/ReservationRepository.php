<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function deleteActiveReservation($book, $user)
    {
        $qb = $this->createQueryBuilder("r");
        $qb
            ->delete()
            ->andWhere("r.book = :book")
            ->andWhere("r.user = :user")
            ->andWhere("r.active = true")
            ->setParameter("book", $book)
            ->setParameter("user", $user)
            ->getQuery()
            ->execute()
        ;

    }

    /**
     * @return bool Returns whether user already has an active reservation
     */
    public function activeReservationExists($book, $user) : bool
    {
        return count( 
            $this->createQueryBuilder("r")
            ->andWhere("r.user = :user")
            ->setParameter("user", $user)
            ->andWhere("r.book = :book")
            ->andWhere("r.active = true")
            ->setParameter("book", $book)
            ->getQuery()
            ->getScalarResult()
        ) > 0 ? true : false;
    }


    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
