<?php

namespace App\Repository;

use App\Entity\Creditnail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Creditnail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Creditnail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Creditnail[]    findAll()
 * @method Creditnail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditnailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Creditnail::class);
    }

    // /**
    //  * @return Creditnail[] Returns an array of Creditnail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Creditnail
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
