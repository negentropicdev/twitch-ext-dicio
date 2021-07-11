<?php

namespace App\Repository;

use App\Entity\RoverlayApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoverlayApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoverlayApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoverlayApp[]    findAll()
 * @method RoverlayApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoverlayAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoverlayApp::class);
    }

    // /**
    //  * @return RoverlayApp[] Returns an array of RoverlayApp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoverlayApp
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
