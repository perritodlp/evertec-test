<?php

namespace App\Repository;

use App\Entity\PaymentAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentAttempt[]    findAll()
 * @method PaymentAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentAttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}