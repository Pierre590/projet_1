<?php

namespace App\Repository;

use App\Entity\Zip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Zip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zip[]    findAll()
 * @method Zip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zip::class);
    }

    // /**
    //  * @return Zip[] Returns an array of Zip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zip
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
