<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findByOperator(User $operator)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.operator = :val')
            ->setParameter('val', $operator)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByGameAndStatus(Game $game, $statuses = [
        Account::STATUS_READY
    ])
    {
        $qb = $this->createQueryBuilder('a');
        return $qb
            ->andWhere('a.game = :game')
            ->andWhere(
                $qb->expr()->in('a.status', ':statuses')
            )
            ->setParameter('game', $game)
            ->setParameter('statuses', $statuses)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Account[] Returns an array of Account objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Account
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
