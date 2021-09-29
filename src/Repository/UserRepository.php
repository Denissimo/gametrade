<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function loadByRoleAsArray($value)
    {
        $operators = $this->loadByRole($value);
        $operatorList = [];

        foreach ($operators as $operator) {
            $operatorList[$operator->getId()] = $operator->getFioUsername();
        }

        return $operatorList;
    }

    /**
     * @param $value
     *
     * @return User[]|array
     */
    public function loadByRole($value)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere(
            $qb->expr()->like('u.roles', ':roles')
        )
            ->setParameter('roles', '%"' . $value . '"%')
            ->orderBy('u.id', 'ASC');

        return $qb->getQuery()
            ->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
