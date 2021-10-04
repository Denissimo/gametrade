<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByManager(User $manager)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.head = :val')
            ->setParameter('val', $manager)
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByOperator(User $operator)
    {
        $qb = $this->createQueryBuilder('t');

        return $qb->where('t.operator = :val')
            ->andWhere(
                $qb->expr()->notIn('t.status', ':statuses')
            )
            ->setParameter('val', $operator)
            ->setParameter('statuses', [
                Task::STATUS_DONE,
                Task::STATUS_CANCELLED,
                Task::STATUS_CONFIRMED
            ])
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
