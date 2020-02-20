<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchWithQueryBuilder( string $searchQuery )
    {
        return $this->createQueryBuilder("b")
            ->andWhere("b.author LIKE :searchQuery")
            ->orWhere("b.title LIKE :searchQuery")
            ->orWhere("b.originalTitle LIKE :searchQuery")
            ->setParameter("searchQuery", "%". $searchQuery . "%")
        ;
    }

    public function findAllWithLimit(int $limit, int $page = 1)
    {
        return $this->createQueryBuilder("b")
            ->setFirstResult($page * $limit - $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function isAvailable($book) : bool
    {
        $result = $this->createQueryBuilder("b")->andWhere("b = :book")->setParameter("book", $book)->andWhere("b.currentQuantity > 0")->getQuery()->getResult();
        return count($result) > 0 ? true : false;
    }
        

    public function findAnyBooks($value): ?array
    {   
        if ($value == "") return null;
        
        
        $sql = "SELECT title, original_title, author, cover, slug
            from book
            where match(title, original_title) against(:val in boolean mode) "
        ;       
        
        $db = $this->getEntityManager()->getConnection();

        $query = $db->prepare($sql);
        $query->execute(["val" => $value . "*"]);

        return $query->fetchAll();
    }
    
    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}