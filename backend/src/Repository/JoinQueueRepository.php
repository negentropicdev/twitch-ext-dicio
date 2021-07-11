<?php

namespace App\Repository;

use App\Entity\JoinQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JoinQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method JoinQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method JoinQueue[]    findAll()
 * @method JoinQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoinQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JoinQueue::class);
    }

    // /**
    //  * @return JoinQueue[] Returns an array of JoinQueue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JoinQueue
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
