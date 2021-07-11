<?php

namespace App\Repository;

use App\Entity\ControlValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ControlValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ControlValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ControlValue[]    findAll()
 * @method ControlValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ControlValue::class);
    }

    // /**
    //  * @return ControlValue[] Returns an array of ControlValue objects
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
    public function findOneBySomeField($value): ?ControlValue
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
