<?php

namespace App\Repository;

use App\Entity\Resa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Resa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resa[]    findAll()
 * @method Resa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resa::class);
    }

    // /**
    //  * @return Resa[] Returns an array of Resa objects
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
    public function findOneBySomeField($value): ?Resa
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
