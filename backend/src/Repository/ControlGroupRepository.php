<?php

namespace App\Repository;

use App\Entity\ControlGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ControlGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ControlGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ControlGroup[]    findAll()
 * @method ControlGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ControlGroup::class);
    }

    // /**
    //  * @return ControlGroup[] Returns an array of ControlGroup objects
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
    public function findOneBySomeField($value): ?ControlGroup
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
