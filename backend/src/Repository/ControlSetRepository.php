<?php

namespace App\Repository;

use App\Entity\ControlSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ControlSet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ControlSet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ControlSet[]    findAll()
 * @method ControlSet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlSetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ControlSet::class);
    }

    // /**
    //  * @return ControlSet[] Returns an array of ControlSet objects
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
    public function findOneBySomeField($value): ?ControlSet
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
